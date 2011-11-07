$(function () {

	Panel = {};

	// TinyMCE setup
	Panel.tinyMCE = new tinymce.Editor('editor-panel', {
		mode : "exact",
		elements : "editor",
		theme : "advanced",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		height : "342",
		theme_advanced_buttons1 : "savebutton,separator,bold,italic,underline,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,bullist,numlist",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : ""
	});
	Panel.tinyMCE.addButton('savebutton', {
		title : 'Save',
		image : 'full/images/save.png',
		onclick : function() {
			if(Panel.jsTree.selected && !Panel.tinyMCE.isHidden()) {
				$.post('full/server.php?server&type=savefile',{ data : Panel.tinyMCE.getContent(), lang : ($.inArray(Panel.jsTree.current_lang, Panel.jsTree.settings.languages) + 1), id : Panel.jsTree.selected.attr("id") });
			}
		}
	});
	Panel.tinyMCE.onChange.add(function(ed, l) {
		Panel.tinyMCE.content_edited = true;
	});

	Panel.tinyMCE.render_state = 0;

	// jsTree setup
	Panel.jsTree = $.tree_create();
	Panel.jsTree.init($("#tree-panel .tree"),{
		data : { type : "json", async : true, url : "full/server.php?server&type=list" },
		languages : [ "en", "de", "fr" ],
		rules : { draggable : "all", multiple : "ctrl" },

		callback : {
			beforechange : function (n, t) { 
				// if(Panel.tinyMCE.content_edited) return confirm("You changed the content, are you sure you want to navigate away, without saving changes?");
				return true;
			}, 
			onchange : function (n) { 
				if(n.id) Panel.loadContent(n.id);
			},

			// THINK OF ROLLBACK!
			oncreate : function (n, r, h, t) {
				if(!Panel.tinyMCE.isHidden()) Panel.tinyMCE.hide();
				$("#editor").html("<p class='message'>Please create the node first</p>");
				Panel.creating = 1;
				Panel.move(n,r,h);
			},
			onrename : function (n,l,t,r) {
				$.get("full/server.php?server&type=rename", { data : $(n).children("a:visible").text(), lang : ($.inArray(t.current_lang, t.settings.languages) + 1), id : n.id });
				if(Panel.creating == 1) {
					Panel.loadContent(n.id);
					Panel.creating = 0;
				}
			},
			onmove : function (n, r, h) {
				Panel.move(n,r,h);
			},
			beforedelete : function (n) {
				return confirm("Are you sure you want to delete?");
			},
			ondelete : function (n) {
				$.get("full/server.php?server&type=delete&id=" + n.id);
				if(!Panel.tinyMCE.isHidden()) Panel.tinyMCE.hide();
				$("#editor").html("<p class='message'>Click a node on the left to edit its content</p>");
			},
			onload : function (t) { 
				//t.select_branch(t.container.find("li:eq(0)"));
			}
		}
	});
	Panel.creating = 0;

	// Functions
	Panel.loadContent = function(id) {
		if(Panel.tinyMCE.render_state == 0) {
			Panel.tinyMCE.onInit.add(function(ed) {
				ed.setContent("");
				ed.render_state = 1;
				Panel.loadContent(id);
			});
			Panel.tinyMCE.render();
		} 
		else {
			if(Panel.tinyMCE.isHidden()) Panel.tinyMCE.show();
			Panel.tinyMCE.setProgressState(1);
			$.get('full/server.php?server&type=loadfile&id=' + id + '&lang=' + ($.inArray(Panel.jsTree.current_lang, Panel.jsTree.settings.languages) + 1), function (data) {
				Panel.tinyMCE.setContent(data); 
				Panel.tinyMCE.content_edited = false;
				Panel.tinyMCE.setProgressState(0);
			});
		}
	}
	Panel.move = function(n,r,h) {
		$.get("full/server.php?server&type=move&id=" + n.id + "&ref_id=" + (r === -1 ? 0 : r.id) + "&move_type=" + h, function (data) {
			if(!n.id) n.id = data;
		});
	}
	Panel.create	= function () { Panel.jsTree.create(false, (Panel.jsTree.container.find("li").size() == 0 ? -1 : false) ); }
	Panel.rename	= function () { Panel.jsTree.rename(); }
	Panel.remove	= function () { Panel.jsTree.remove(); }
	Panel.copy		= function () { if(Panel.jsTree.selected) { Panel.jsTree.copy(); } }
	Panel.cut		= function () { if(Panel.jsTree.selected) { Panel.jsTree.cut(); } }
	Panel.paste		= function () { Panel.jsTree.paste(); }

	// Keyboard shortcuts
	$(document)
		.bind('keydown', {combi:'up',		disableInInput: true} , function() { Panel.jsTree.get_prev(); return false; })
		.bind('keydown', {combi:'down',		disableInInput: true} , function() { Panel.jsTree.get_next(); return false; })
		.bind('keydown', {combi:'left',		disableInInput: true} , function() { Panel.jsTree.get_left(); return false; })
		.bind('keydown', {combi:'right',	disableInInput: true} , function() { Panel.jsTree.get_right(); return false; })
		.bind('keydown', {combi:'return',	disableInInput: true} , function() { if(Panel.jsTree.hovered) Panel.jsTree.select_branch(Panel.jsTree.hovered); return false; })
		.bind('keydown', {combi:'f2',		disableInInput: true} , function() { if(Panel.jsTree.hovered) Panel.jsTree.rename(); return false; })
		.bind('keydown', {combi:'del',		disableInInput: true} , function() { if(Panel.jsTree.selected) Panel.jsTree.remove(); return false; })
		.bind('keydown', {combi:'insert',	disableInInput: true} , function() { if(Panel.jsTree.selected) Panel.jsTree.create(); return false; })
		.bind('keydown', {combi:'ctrl+c',	disableInInput: true} , function() { Panel.jsTree.copy(); return false; })
		.bind('keydown', {combi:'ctrl+x',	disableInInput: true} , function() { Panel.jsTree.cut(); return false; })
		.bind('keydown', {combi:'ctrl+v',	disableInInput: true} , function() { Panel.jsTree.paste(); return false; })

	// Interface hooks
	$("#tree-panel .menu")
		.find("a").not(".lang")
			.bind("click", function () {
				try { Panel[$(this).attr("rel")](); } catch(err) { }
				this.blur();
			})
			.end().end()
		.children(".cmenu")
			.hover( function () { $(this).addClass("hover"); }, function () { $(this).removeClass("hover") })

	$("#tree-panel .menu a.lang")
		.live("click", function (event) {
			Panel.jsTree.show_lang(($(this).attr("href").replace(/.*?#/ig, "") - 1));  
			$(this).clone().prependTo($(this).parent()); 
			$(this).remove(); 
			if(Panel.jsTree.selected) Panel.loadContent(Panel.jsTree.selected.attr("id"));
			event.preventDefault();
			event.stopPropagation();
			return false;
		});
	
});

