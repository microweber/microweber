/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){

    Ext.QuickTips.init();

    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';
    
    var bd = Ext.getBody();
    
    bd.createChild({tag: 'h2', html: 'Dutch Form'})
    
    // simple form
    var simple = new Ext.FormPanel({
        labelWidth: 100, // label settings here cascade unless overridden
        url:'save-form.php',
        frame:true,
        title: 'Contact Informatie (Dutch)',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {width: 220},
        defaultType: 'textfield',

        items: [{
                fieldLabel: 'Voornaam',
                name: 'voornaam',
                allowBlank:false
            },{
                fieldLabel: 'Achternaam',
                name: 'achternaam'
            },{
                fieldLabel: 'Tussenvoegsel',
                width: 50,
                name: 'tussenvoegsel'
            },{
                fieldLabel: 'Bedrijf',
                name: 'bedrijf'
            },  new Ext.form.ComboBox({
            fieldLabel: 'Provincie',
            hiddenName: 'state',
            store: new Ext.data.SimpleStore({
                fields: ['provincie'],
                data : Ext.exampledata.dutch_provinces // from dutch-provinces.js
            }),
            displayField: 'provincie',
            typeAhead: true,
            mode: 'local',
            triggerAction: 'all',
            emptyText:'Kies een provincie...',
            selectOnFocus:true,
            width:190
            }), {
                fieldLabel: 'E-mail',
                name: 'email',
                vtype:'email'
            }, new Ext.form.DateField({
                fieldLabel: 'Geb. Datum',
                name: 'geb_datum'
            })
        ],

        buttons: [{
            text: 'Opslaan'
        },{
            text: 'Annuleren'
        }]
    });

    simple.render(document.body);
});