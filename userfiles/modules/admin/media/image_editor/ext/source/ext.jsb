<?xml version="1.0" encoding="utf-8"?>
<project path="" name="Ext - JS Lib" author="Ext JS, LLC" version="2.2" copyright="Ext JS Library $version&#xD;&#xA;Copyright(c) 2006-2008, $author.&#xD;&#xA;licensing@extjs.com&#xD;&#xA;&#xD;&#xA;http://extjs.com/license" output="C:\apps\www\deploy\ext-2.2\" source="True" source-dir="$output\source" minify="True" min-dir="$output\build" doc="False" doc-dir="$output\docs" min-dair="$output\build">
  <directory name="" />
  <target name="Core" file="$output\ext-core.js" debug="True" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="core\DomHelper.js" />
    <include name="core\Template.js" />
    <include name="core\DomQuery.js" />
    <include name="util\Observable.js" />
    <include name="core\EventManager.js" />
    <include name="core\Element.js" />
    <include name="core\Fx.js" />
    <include name="core\CompositeElement.js" />
    <include name="data\Connection.js" />
    <include name="core\UpdateManager.js" />
    <include name="util\DelayedTask.js" />
  </target>
  <target name="Everything" file="$output\ext-all.js" debug="True" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="core\DomHelper.js" />
    <include name="core\Template.js" />
    <include name="core\DomQuery.js" />
    <include name="util\Observable.js" />
    <include name="core\EventManager.js" />
    <include name="core\Element.js" />
    <include name="core\Fx.js" />
    <include name="core\CompositeElement.js" />
    <include name="data\Connection.js" />
    <include name="core\UpdateManager.js" />
    <include name="util\Date.js" />
    <include name="util\DelayedTask.js" />
    <include name="util\TaskMgr.js" />
    <include name="util\MixedCollection.js" />
    <include name="util\JSON.js" />
    <include name="util\Format.js" />
    <include name="util\XTemplate.js" />
    <include name="util\CSS.js" />
    <include name="util\ClickRepeater.js" />
    <include name="util\KeyNav.js" />
    <include name="util\KeyMap.js" />
    <include name="util\TextMetrics.js" />
    <include name="dd\DDCore.js" />
    <include name="dd\DragTracker.js" />
    <include name="dd\ScrollManager.js" />
    <include name="dd\Registry.js" />
    <include name="dd\StatusProxy.js" />
    <include name="dd\DragSource.js" />
    <include name="dd\DropTarget.js" />
    <include name="dd\DragZone.js" />
    <include name="dd\DropZone.js" />
    <include name="data\SortTypes.js" />
    <include name="data\Record.js" />
    <include name="data\StoreMgr.js" />
    <include name="data\Store.js" />
    <include name="data\SimpleStore.js" />
    <include name="data\JsonStore.js" />
    <include name="data\DataField.js" />
    <include name="data\DataReader.js" />
    <include name="data\DataProxy.js" />
    <include name="data\MemoryProxy.js" />
    <include name="data\HttpProxy.js" />
    <include name="data\ScriptTagProxy.js" />
    <include name="data\JsonReader.js" />
    <include name="data\XmlReader.js" />
    <include name="data\ArrayReader.js" />
    <include name="data\Tree.js" />
    <include name="data\GroupingStore.js" />
    <include name="widgets\ComponentMgr.js" />
    <include name="widgets\Component.js" />
    <include name="widgets\Action.js" />
    <include name="widgets\Layer.js" />
    <include name="widgets\Shadow.js" />
    <include name="widgets\BoxComponent.js" />
    <include name="widgets\SplitBar.js" />
    <include name="widgets\Container.js" />
    <include name="widgets\layout\ContainerLayout.js" />
    <include name="widgets\layout\FitLayout.js" />
    <include name="widgets\layout\CardLayout.js" />
    <include name="widgets\layout\AnchorLayout.js" />
    <include name="widgets\layout\ColumnLayout.js" />
    <include name="widgets\layout\BorderLayout.js" />
    <include name="widgets\layout\FormLayout.js" />
    <include name="widgets\layout\AccordionLayout.js" />
    <include name="widgets\layout\TableLayout.js" />
    <include name="widgets\layout\AbsoluteLayout.js" />
    <include name="widgets\Viewport.js" />
    <include name="widgets\Panel.js" />
    <include name="widgets\Window.js" />
    <include name="widgets\WindowManager.js" />
    <include name="widgets\PanelDD.js" />
    <include name="state\Provider.js" />
    <include name="state\StateManager.js" />
    <include name="state\CookieProvider.js" />
    <include name="widgets\DataView.js" />
    <include name="widgets\ColorPalette.js" />
    <include name="widgets\DatePicker.js" />
    <include name="widgets\TabPanel.js" />
    <include name="widgets\Button.js" />
    <include name="widgets\SplitButton.js" />
    <include name="widgets\CycleButton.js" />
    <include name="widgets\Toolbar.js" />
    <include name="widgets\PagingToolbar.js" />
    <include name="widgets\Resizable.js" />
    <include name="widgets\Editor.js" />
    <include name="widgets\MessageBox.js" />
    <include name="widgets\tips\Tip.js" />
    <include name="widgets\tips\ToolTip.js" />
    <include name="widgets\tips\QuickTip.js" />
    <include name="widgets\tips\QuickTips.js" />
    <include name="widgets\tree\TreePanel.js" />
    <include name="widgets\tree\TreeEventModel.js" />
    <include name="widgets\tree\TreeSelectionModel.js" />
    <include name="widgets\tree\TreeNode.js" />
    <include name="widgets\tree\AsyncTreeNode.js" />
    <include name="widgets\tree\TreeNodeUI.js" />
    <include name="widgets\tree\TreeLoader.js" />
    <include name="widgets\tree\TreeFilter.js" />
    <include name="widgets\tree\TreeSorter.js" />
    <include name="widgets\tree\TreeDropZone.js" />
    <include name="widgets\tree\TreeDragZone.js" />
    <include name="widgets\tree\TreeEditor.js" />
    <include name="widgets\menu\Menu.js" />
    <include name="widgets\menu\MenuMgr.js" />
    <include name="widgets\menu\BaseItem.js" />
    <include name="widgets\menu\TextItem.js" />
    <include name="widgets\menu\Separator.js" />
    <include name="widgets\menu\Item.js" />
    <include name="widgets\menu\CheckItem.js" />
    <include name="widgets\menu\Adapter.js" />
    <include name="widgets\menu\DateItem.js" />
    <include name="widgets\menu\ColorItem.js" />
    <include name="widgets\menu\DateMenu.js" />
    <include name="widgets\menu\ColorMenu.js" />
    <include name="widgets\form\Field.js" />
    <include name="widgets\form\TextField.js" />
    <include name="widgets\form\TriggerField.js" />
    <include name="widgets\form\TextArea.js" />
    <include name="widgets\form\NumberField.js" />
    <include name="widgets\form\DateField.js" />
    <include name="widgets\form\Combo.js" />
    <include name="widgets\form\Checkbox.js" />
    <include name="widgets\form\CheckboxGroup.js" />
    <include name="widgets\form\Radio.js" />
    <include name="widgets\form\RadioGroup.js" />
    <include name="widgets\form\Hidden.js" />
    <include name="widgets\form\BasicForm.js" />
    <include name="widgets\form\Form.js" />
    <include name="widgets\form\FieldSet.js" />
    <include name="widgets\form\HtmlEditor.js" />
    <include name="widgets\form\TimeField.js" />
    <include name="widgets\form\Label.js" />
    <include name="widgets\form\Action.js" />
    <include name="widgets\form\VTypes.js" />
    <include name="widgets\grid\GridPanel.js" />
    <include name="widgets\grid\GridView.js" />
    <include name="widgets\grid\GroupingView.js" />
    <include name="widgets\grid\ColumnDD.js" />
    <include name="widgets\grid\ColumnSplitDD.js" />
    <include name="widgets\grid\GridDD.js" />
    <include name="widgets\grid\ColumnModel.js" />
    <include name="widgets\grid\AbstractSelectionModel.js" />
    <include name="widgets\grid\RowSelectionModel.js" />
    <include name="widgets\grid\CellSelectionModel.js" />
    <include name="widgets\grid\EditorGrid.js" />
    <include name="widgets\grid\GridEditor.js" />
    <include name="widgets\grid\PropertyGrid.js" />
    <include name="widgets\grid\RowNumberer.js" />
    <include name="widgets\grid\CheckboxSelectionModel.js" />
    <include name="widgets\LoadMask.js" />
    <include name="widgets\ProgressBar.js" />
    <include name="widgets\Slider.js" />
    <include name="widgets\StatusBar.js" />
    <include name="util\History.js" />
    <include name="debug.js" />
  </target>
  <file name="layout\LayoutRegionLite.js" path="layout" />
  <file name="DDScrollManager.js" path="" />
  <file name="grid\AbstractColumnModel.js" path="grid" />
  <file name="data\ArrayAdapter.js" path="data" />
  <file name="data\DataAdapter.js" path="data" />
  <file name="data\HttpAdapter.js" path="data" />
  <file name="data\JsonAdapter.js" path="data" />
  <file name="data\ArrayProxy.js" path="data" />
  <file name="widgets\SimpleMenu.js" path="widgets" />
  <file name="CSS.js" path="" />
  <file name="CustomTagReader.js" path="" />
  <file name="Format.js" path="" />
  <file name="JSON.js" path="" />
  <file name="MixedCollection.js" path="" />
  <file name="data\DataSource.js" path="data" />
  <file name="license.txt" path="" />
  <file name="yui-ext-dl.jsb" path="" />
  <file name="yui-ext.jsb" path="" />
  <file name="form\FloatingEditor.js" path="form" />
  <file name="anim\Actor.js" path="anim" />
  <file name="anim\Animator.js" path="anim" />
  <file name="data\AbstractDataModel.js" path="data" />
  <file name="data\DataModel.js" path="data" />
  <file name="data\DataSet.js" path="data" />
  <file name="data\DataStore.js" path="data" />
  <file name="data\DefaultDataModel.js" path="data" />
  <file name="data\JSONDataModel.js" path="data" />
  <file name="data\LoadableDataModel.js" path="data" />
  <file name="data\Set.js" path="data" />
  <file name="data\TableModel.js" path="data" />
  <file name="data\XMLDataModel.js" path="data" />
  <file name="form\DateField.js" path="form" />
  <file name="form\Field.js" path="form" />
  <file name="form\FieldGroup.js" path="form" />
  <file name="form\Form.js" path="form" />
  <file name="form\NumberField.js" path="form" />
  <file name="form\Select.js" path="form" />
  <file name="form\TextArea.js" path="form" />
  <file name="form\TextField.js" path="form" />
  <file name="grid\editor\CellEditor.js" path="grid\editor" />
  <file name="grid\editor\CheckboxEditor.js" path="grid\editor" />
  <file name="grid\editor\DateEditor.js" path="grid\editor" />
  <file name="grid\editor\NumberEditor.js" path="grid\editor" />
  <file name="grid\editor\SelectEditor.js" path="grid\editor" />
  <file name="grid\editor\TextEditor.js" path="grid\editor" />
  <file name="grid\AbstractGridView.js" path="grid" />
  <file name="grid\AbstractSelectionModel.js" path="grid" />
  <file name="grid\CellSelectionModel.js" path="grid" />
  <file name="grid\DefaultColumnModel.js" path="grid" />
  <file name="grid\EditorGrid.js" path="grid" />
  <file name="grid\EditorSelectionModel.js" path="grid" />
  <file name="grid\Grid.js" path="grid" />
  <file name="grid\GridDD.js" path="grid" />
  <file name="grid\GridEditor.js" path="grid" />
  <file name="grid\GridView.js" path="grid" />
  <file name="grid\GridViewLite.js" path="grid" />
  <file name="grid\PagedGridView.js" path="grid" />
  <file name="grid\RowSelectionModel.js" path="grid" />
  <file name="grid\SelectionModel.js" path="grid" />
  <file name="layout\BasicLayoutRegion.js" path="layout" />
  <file name="layout\BorderLayout.js" path="layout" />
  <file name="layout\BorderLayoutRegions.js" path="layout" />
  <file name="layout\ContentPanels.js" path="layout" />
  <file name="layout\LayoutManager.js" path="layout" />
  <file name="layout\LayoutRegion.js" path="layout" />
  <file name="layout\LayoutStateManager.js" path="layout" />
  <file name="layout\SplitLayoutRegion.js" path="layout" />
  <file name="menu\Adapter.js" path="menu" />
  <file name="menu\BaseItem.js" path="menu" />
  <file name="menu\CheckItem.js" path="menu" />
  <file name="menu\ColorItem.js" path="menu" />
  <file name="menu\DateItem.js" path="menu" />
  <file name="menu\DateMenu.js" path="menu" />
  <file name="menu\Item.js" path="menu" />
  <file name="menu\Menu.js" path="menu" />
  <file name="menu\MenuMgr.js" path="menu" />
  <file name="menu\Separator.js" path="menu" />
  <file name="menu\TextItem.js" path="menu" />
  <file name="tree\AsyncTreeNode.js" path="tree" />
  <file name="tree\TreeDragZone.js" path="tree" />
  <file name="tree\TreeDropZone.js" path="tree" />
  <file name="tree\TreeFilter.js" path="tree" />
  <file name="tree\TreeLoader.js" path="tree" />
  <file name="tree\TreeNode.js" path="tree" />
  <file name="tree\TreeNodeUI.js" path="tree" />
  <file name="tree\TreePanel.js" path="tree" />
  <file name="tree\TreeSelectionModel.js" path="tree" />
  <file name="tree\TreeSorter.js" path="tree" />
  <file name="widgets\BasicDialog2.js" path="widgets" />
  <file name="widgets\InlineEditor.js" path="widgets" />
  <file name="widgets\TaskPanel.js" path="widgets" />
  <file name="widgets\TemplateView.js" path="widgets" />
  <file name="Anims.js" path="" />
  <file name="Bench.js" path="" />
  <file name="compat.js" path="" />
  <file name="CompositeElement.js" path="" />
  <file name="Date.js" path="" />
  <file name="DomHelper.js" path="" />
  <file name="DomQuery.js" path="" />
  <file name="Element.js" path="" />
  <file name="EventManager.js" path="" />
  <file name="Ext.js" path="" />
  <file name="Fx.js" path="" />
  <file name="KeyMap.js" path="" />
  <file name="KeyNav.js" path="" />
  <file name="Layer.js" path="" />
  <file name="State.js" path="" />
  <file name="Template.js" path="" />
  <file name="UpdateManager.js" path="" />
  <file name="yutil.js" path="" />
  <file name="widgets\form\Select.js" path="widgets\form" />
  <file name="widgets\Notifier.js" path="widgets" />
  <file name="yui\dragdrop.js" path="yui" />
  <file name="yui-overrides.js" path="" />
  <target name="YUI" file="$output\adapter\yui\ext-yui-adapter.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="core\Ext.js" />
    <include name="adapter\yui-bridge.js" />
  </target>
  <target name="Menus" file="$output\package\menu\menus.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\menu\Menu.js" />
    <include name="widgets\menu\MenuMgr.js" />
    <include name="widgets\menu\BaseItem.js" />
    <include name="widgets\menu\TextItem.js" />
    <include name="widgets\menu\Separator.js" />
    <include name="widgets\menu\Item.js" />
    <include name="widgets\menu\CheckItem.js" />
    <include name="widgets\menu\Adapter.js" />
    <include name="widgets\menu\DateItem.js" />
    <include name="widgets\menu\ColorItem.js" />
    <include name="widgets\menu\DateMenu.js" />
    <include name="widgets\menu\ColorMenu.js" />
  </target>
  <target name="Tree" file="$output\package\tree\tree.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="data\Tree.js" />
    <include name="widgets\tree\TreeEventModel.js" />
    <include name="widgets\tree\TreePanel.js" />
    <include name="widgets\tree\TreeSelectionModel.js" />
    <include name="widgets\tree\TreeNode.js" />
    <include name="widgets\tree\AsyncTreeNode.js" />
    <include name="widgets\tree\TreeNodeUI.js" />
    <include name="widgets\tree\TreeLoader.js" />
    <include name="widgets\tree\TreeFilter.js" />
    <include name="widgets\tree\TreeSorter.js" />
    <include name="widgets\tree\TreeDropZone.js" />
    <include name="widgets\tree\TreeDragZone.js" />
    <include name="widgets\tree\TreeEditor.js" />
  </target>
  <target name="Grid" file="$output\package\grid\grid.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\grid\Grid.js" />
    <include name="widgets\grid\AbstractGridView.js" />
    <include name="widgets\grid\GridView.js" />
    <include name="widgets\grid\ColumnModel.js" />
    <include name="widgets\grid\AbstractSelectionModel.js" />
    <include name="widgets\grid\RowSelectionModel.js" />
    <include name="widgets\grid\CellSelectionModel.js" />
    <include name="widgets\grid\ColumnDD.js" />
    <include name="widgets\grid\ColumnSplitDD.js" />
    <include name="widgets\grid\GridDD.js" />
  </target>
  <target name="Dialog" file="$output\package\dialog\dialogs.js" debug="True" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\BasicDialog.js" />
    <include name="widgets\MessageBox.js" />
  </target>
  <target name="Form" file="$output\package\form\form.js" debug="True" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\form\Field.js" />
    <include name="widgets\form\TextField.js" />
    <include name="widgets\form\TriggerField.js" />
    <include name="widgets\form\TextArea.js" />
    <include name="widgets\form\NumberField.js" />
    <include name="widgets\form\Label.js" />
    <include name="widgets\form\DateField.js" />
    <include name="widgets\form\Checkbox.js" />
    <include name="widgets\form\Radio.js" />
    <include name="widgets\form\Combo.js" />
    <include name="widgets\Editor.js" />
    <include name="widgets\form\BasicForm.js" />
    <include name="widgets\form\Form.js" />
    <include name="widgets\form\Action.js" />
    <include name="widgets\form\VTypes.js" />
  </target>
  <target name="Button" file="$output\package\button\button.js" debug="True" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\Button.js" />
    <include name="widgets\SplitButton.js" />
  </target>
  <target name="Grid - Edit" file="$output\package\grid\edit-grid.js" debug="True" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\grid\EditorGrid.js" />
    <include name="widgets\grid\GridEditor.js" />
    <include name="widgets\grid\PropertyGrid.js" />
  </target>
  <target name="JQUERY" file="$output\adapter\jquery\ext-jquery-adapter.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="core\Ext.js" />
    <include name="adapter\jquery-bridge.js" />
  </target>
  <target name="Utilities" file="$output\package\util.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="util\DelayedTask.js" />
    <include name="util\MixedCollection.js" />
    <include name="util\JSON.js" />
    <include name="util\Format.js" />
    <include name="util\CSS.js" />
    <include name="util\ClickRepeater.js" />
    <include name="util\KeyNav.js" />
    <include name="util\KeyMap.js" />
    <include name="util\TextMetrics.js" />
  </target>
  <target name="Drag Drop" file="$output\package\dragdrop\dragdrop.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="dd\DDCore.js" />
    <include name="dd\ScrollManager.js" />
    <include name="dd\Registry.js" />
    <include name="dd\StatusProxy.js" />
    <include name="dd\DragSource.js" />
    <include name="dd\DropTarget.js" />
    <include name="dd\DragZone.js" />
    <include name="dd\DropZone.js" />
  </target>
  <target name="Data" file="$output\package\data\data.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="data\SortTypes.js" />
    <include name="data\Record.js" />
    <include name="data\StoreMgr.js" />
    <include name="data\Store.js" />
    <include name="data\SimpleStore.js" />
    <include name="data\Connection.js" />
    <include name="data\DataField.js" />
    <include name="data\DataReader.js" />
    <include name="data\DataProxy.js" />
    <include name="data\MemoryProxy.js" />
    <include name="data\HttpProxy.js" />
    <include name="data\ScriptTagProxy.js" />
    <include name="data\JsonReader.js" />
    <include name="data\XmlReader.js" />
    <include name="data\ArrayReader.js" />
  </target>
  <target name="Widget Core" file="$output\package\widget-core.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\ComponentMgr.js" />
    <include name="widgets\Component.js" />
    <include name="widgets\BoxComponent.js" />
    <include name="widgets\Layer.js" />
    <include name="widgets\Shadow.js" />
  </target>
  <target name="color-palette" file="$output\package\color-palette.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\ColorPalette.js" />
  </target>
  <target name="Date Picker" file="$output\package\datepicker\datepicker.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\DatePicker.js" />
  </target>
  <target name="Tabs" file="$output\package\tabs\tabs.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\TabPanel.js" />
  </target>
  <target name="Toolbar" file="$output\package\toolbar\toolbar.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\Toolbar.js" />
    <include name="widgets\PagingToolbar.js" />
  </target>
  <target name="Resizable" file="$output\package\resizable.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\Resizable.js" />
  </target>
  <target name="SplitBar" file="$output\package\splitbar.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\SplitBar.js" />
  </target>
  <target name="QTips" file="$output\package\qtips\qtips.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="widgets\tips\Tip.js" />
    <include name="widgets\tips\ToolTip.js" />
    <include name="widgets\tips\QuickTip.js" />
    <include name="widgets\tips\QuickTips.js" />
  </target>
  <file name="util\CustomTagReader.js" path="util" />
  <target name="Date" file="$output\package\date.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="util\Date.js" />
  </target>
  <file name="widgets\Combo.js" path="widgets" />
  <target name="Prototype" file="$output\adapter\prototype\ext-prototype-adapter.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="core\Ext.js" />
    <include name="adapter\prototype-bridge.js" />
  </target>
  <file name="widgets\form\Validators.js" path="widgets\form" />
  <file name="experimental\ext-lang-en.js" path="experimental" />
  <file name="experimental\jquery-bridge.js" path="experimental" />
  <file name="experimental\prototype-bridge.js" path="experimental" />
  <file name="experimental\yui-bridge.js" path="experimental" />
  <file name="widgets\Frame.js" path="widgets" />
  <file name="widgets\layout\AutoLayout.js" path="widgets\layout" />
  <file name="widgets\TabPanel2.js" path="widgets" />
  <file name="widgets\panel\ButtonPanel.js" path="widgets\panel" />
  <file name="experimental\Ajax.js" path="experimental" />
  <file name="experimental\Anims.js" path="experimental" />
  <file name="experimental\BasicDialog2.js" path="experimental" />
  <file name="experimental\BasicGridView.js" path="experimental" />
  <file name="experimental\GridView3.js" path="experimental" />
  <file name="experimental\GridViewUI.js" path="experimental" />
  <file name="experimental\ModelEventHandler.js" path="experimental" />
  <file name="experimental\TaskPanel.js" path="experimental" />
  <file name="experimental\UIEventHandler.js" path="experimental" />
  <file name="legacy\Actor.js" path="legacy" />
  <file name="legacy\Animator.js" path="legacy" />
  <file name="legacy\compat.js" path="legacy" />
  <file name="legacy\InlineEditor.js" path="legacy" />
  <file name="widgets\grid\Grid.js" path="widgets\grid" />
  <file name="widgets\panel\AutoLayout.js" path="widgets\panel" />
  <file name="widgets\panel\BorderLayout.js" path="widgets\panel" />
  <file name="widgets\panel\Container.js" path="widgets\panel" />
  <file name="widgets\panel\ContainerLayout.js" path="widgets\panel" />
  <file name="widgets\panel\Grid.js" path="widgets\panel" />
  <file name="widgets\panel\Panel.js" path="widgets\panel" />
  <file name="widgets\panel\TabPanel.js" path="widgets\panel" />
  <file name="widgets\panel\TreePanel.js" path="widgets\panel" />
  <file name="widgets\panel\Viewport.js" path="widgets\panel" />
  <file name="widgets\panel\Window.js" path="widgets\panel" />
  <file name="widgets\panel\WindowManager.js" path="widgets\panel" />
  <file name="widgets\BasicDialog.js" path="widgets" />
  <file name="experimental\GridExtensions.js" path="experimental" />
  <file name="widgets\layout\BasicLayoutRegion.js" path="widgets\layout" />
  <file name="widgets\layout\BorderLayoutRegions.js" path="widgets\layout" />
  <file name="widgets\layout\ContentPanels.js" path="widgets\layout" />
  <file name="widgets\layout\LayoutManager.js" path="widgets\layout" />
  <file name="widgets\layout\LayoutRegion.js" path="widgets\layout" />
  <file name="widgets\layout\LayoutStateManager.js" path="widgets\layout" />
  <file name="widgets\layout\ReaderLayout.js" path="widgets\layout" />
  <file name="widgets\layout\SplitLayoutRegion.js" path="widgets\layout" />
  <target name="Ext Base" file="$output\adapter\ext\ext-base.js" debug="False" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="core\Ext.js" />
    <include name="adapter\ext-base.js" />
  </target>
  <file name="widgets\form\Editor.js" path="widgets\form" />
  <file name="experimental\ext-base.js" path="experimental" />
  <file name="ext.jsb" path="" />
  <file name="widgets\ViewPanel.js" path="widgets" />
  <file name="util\MasterTemplate.js" path="util" />
  <file name="widgets\form\Layout.js" path="widgets\form" />
  <file name="widgets\BorderLayout.js" path="widgets" />
  <file name="widgets\ColumnLayout.js" path="widgets" />
  <file name="widgets\ContainerLayout.js" path="widgets" />
  <file name="widgets\JsonView.js" path="widgets" />
  <file name="widgets\MenuButton.js" path="widgets" />
  <file name="widgets\View.js" path="widgets" />
  <file name="widgets\grid\AbstractGridView.js" path="widgets\grid" />
  <file name="state\State.js" path="state" />
  <file name="widgets\layout\AccordianLayout.js" path="widgets\layout" />
  <file name="widgets\QuickTips.js" path="widgets" />
  <file name="locale\ext-lang-sp.js" path="locale" />
  <file name="locale\ext-lang-no.js" path="locale" />
  <file name="widgets\layout\AbsoluteForm.js" path="widgets\layout" />
  <file name="widgets\layout\AbsoluteFormLayout.js" path="widgets\layout" />
  <file name="widgets\grid\GridColumn.js" path="widgets\grid" />
  <file name="legacy\layout\BasicLayoutRegion.js" path="legacy\layout" />
  <file name="legacy\layout\BorderLayout.js" path="legacy\layout" />
  <file name="legacy\layout\BorderLayoutRegions.js" path="legacy\layout" />
  <file name="legacy\layout\ContentPanels.js" path="legacy\layout" />
  <file name="legacy\layout\LayoutManager.js" path="legacy\layout" />
  <file name="legacy\layout\LayoutRegion.js" path="legacy\layout" />
  <file name="legacy\layout\LayoutStateManager.js" path="legacy\layout" />
  <file name="legacy\layout\ReaderLayout.js" path="legacy\layout" />
  <file name="legacy\layout\SplitLayoutRegion.js" path="legacy\layout" />
  <file name="legacy\AbstractGridView.js" path="legacy" />
  <file name="legacy\BasicDialog.js" path="legacy" />
  <file name="legacy\GridView2.js" path="legacy" />
  <file name="legacy\JsonView.js" path="legacy" />
  <file name="legacy\MasterTemplate.js" path="legacy" />
  <file name="legacy\View.js" path="legacy" />
  <file name="widgets\grid\Column.js" path="widgets\grid" />
  <target name="extjs.com" file="c:\apps\www\v3\assets\js\extjs.js" debug="True" shorthand="False" shorthand-list="YAHOO.util.Dom.setStyle&#xD;&#xA;YAHOO.util.Dom.getStyle&#xD;&#xA;YAHOO.util.Dom.getRegion&#xD;&#xA;YAHOO.util.Dom.getViewportHeight&#xD;&#xA;YAHOO.util.Dom.getViewportWidth&#xD;&#xA;YAHOO.util.Dom.get&#xD;&#xA;YAHOO.util.Dom.getXY&#xD;&#xA;YAHOO.util.Dom.setXY&#xD;&#xA;YAHOO.util.CustomEvent&#xD;&#xA;YAHOO.util.Event.addListener&#xD;&#xA;YAHOO.util.Event.getEvent&#xD;&#xA;YAHOO.util.Event.getTarget&#xD;&#xA;YAHOO.util.Event.preventDefault&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Event.stopPropagation&#xD;&#xA;YAHOO.util.Event.stopEvent&#xD;&#xA;YAHOO.util.Anim&#xD;&#xA;YAHOO.util.Motion&#xD;&#xA;YAHOO.util.Connect.asyncRequest&#xD;&#xA;YAHOO.util.Connect.setForm&#xD;&#xA;YAHOO.util.Dom&#xD;&#xA;YAHOO.util.Event">
    <include name="core\Ext.js" />
    <include name="adapter\ext-base.js" />
    <include name="core\DomHelper.js" />
    <include name="core\Template.js" />
    <include name="core\DomQuery.js" />
    <include name="util\Observable.js" />
    <include name="core\EventManager.js" />
    <include name="core\Element.js" />
    <include name="core\Fx.js" />
    <include name="core\CompositeElement.js" />
    <include name="data\Connection.js" />
    <include name="core\UpdateManager.js" />
    <include name="util\DelayedTask.js" />
    <include name="util\XTemplate.js" />
    <include name="widgets\Layer.js" />
    <include name="widgets\Shadow.js" />
  </target>
  <file name="adapter\ext-base.js" path="adapter" />
  <file name="adapter\jquery-bridge.js" path="adapter" />
  <file name="adapter\prototype-bridge.js" path="adapter" />
  <file name="adapter\yui-bridge.js" path="adapter" />
  <file name="core\CompositeElement.js" path="core" />
  <file name="core\DomHelper.js" path="core" />
  <file name="core\DomQuery.js" path="core" />
  <file name="core\Element.js" path="core" />
  <file name="core\EventManager.js" path="core" />
  <file name="core\Ext.js" path="core" />
  <file name="core\Fx.js" path="core" />
  <file name="core\Template.js" path="core" />
  <file name="core\UpdateManager.js" path="core" />
  <file name="data\ArrayReader.js" path="data" />
  <file name="data\Connection.js" path="data" />
  <file name="data\DataField.js" path="data" />
  <file name="data\DataProxy.js" path="data" />
  <file name="data\DataReader.js" path="data" />
  <file name="data\GroupingStore.js" path="data" />
  <file name="data\HttpProxy.js" path="data" />
  <file name="data\JsonReader.js" path="data" />
  <file name="data\JsonStore.js" path="data" />
  <file name="data\MemoryProxy.js" path="data" />
  <file name="data\Record.js" path="data" />
  <file name="data\ScriptTagProxy.js" path="data" />
  <file name="data\SimpleStore.js" path="data" />
  <file name="data\SortTypes.js" path="data" />
  <file name="data\Store.js" path="data" />
  <file name="data\StoreMgr.js" path="data" />
  <file name="data\Tree.js" path="data" />
  <file name="data\XmlReader.js" path="data" />
  <file name="dd\DDCore.js" path="dd" />
  <file name="dd\DragSource.js" path="dd" />
  <file name="dd\DragTracker.js" path="dd" />
  <file name="dd\DragZone.js" path="dd" />
  <file name="dd\DropTarget.js" path="dd" />
  <file name="dd\DropZone.js" path="dd" />
  <file name="dd\Registry.js" path="dd" />
  <file name="dd\ScrollManager.js" path="dd" />
  <file name="dd\StatusProxy.js" path="dd" />
  <file name="locale\ext-lang-af.js" path="locale" />
  <file name="locale\ext-lang-bg.js" path="locale" />
  <file name="locale\ext-lang-ca.js" path="locale" />
  <file name="locale\ext-lang-cs.js" path="locale" />
  <file name="locale\ext-lang-da.js" path="locale" />
  <file name="locale\ext-lang-de.js" path="locale" />
  <file name="locale\ext-lang-el_GR.js" path="locale" />
  <file name="locale\ext-lang-en.js" path="locale" />
  <file name="locale\ext-lang-en_UK.js" path="locale" />
  <file name="locale\ext-lang-es.js" path="locale" />
  <file name="locale\ext-lang-fa.js" path="locale" />
  <file name="locale\ext-lang-fr.js" path="locale" />
  <file name="locale\ext-lang-fr_CA.js" path="locale" />
  <file name="locale\ext-lang-gr.js" path="locale" />
  <file name="locale\ext-lang-he.js" path="locale" />
  <file name="locale\ext-lang-hr.js" path="locale" />
  <file name="locale\ext-lang-hu.js" path="locale" />
  <file name="locale\ext-lang-id.js" path="locale" />
  <file name="locale\ext-lang-it.js" path="locale" />
  <file name="locale\ext-lang-ja.js" path="locale" />
  <file name="locale\ext-lang-ko.js" path="locale" />
  <file name="locale\ext-lang-lt.js" path="locale" />
  <file name="locale\ext-lang-lv.js" path="locale" />
  <file name="locale\ext-lang-mk.js" path="locale" />
  <file name="locale\ext-lang-nl.js" path="locale" />
  <file name="locale\ext-lang-no_NB.js" path="locale" />
  <file name="locale\ext-lang-no_NN.js" path="locale" />
  <file name="locale\ext-lang-pl.js" path="locale" />
  <file name="locale\ext-lang-pt.js" path="locale" />
  <file name="locale\ext-lang-pt_BR.js" path="locale" />
  <file name="locale\ext-lang-ro.js" path="locale" />
  <file name="locale\ext-lang-ru.js" path="locale" />
  <file name="locale\ext-lang-sk.js" path="locale" />
  <file name="locale\ext-lang-sl.js" path="locale" />
  <file name="locale\ext-lang-sr.js" path="locale" />
  <file name="locale\ext-lang-sr_RS.js" path="locale" />
  <file name="locale\ext-lang-sv_SE.js" path="locale" />
  <file name="locale\ext-lang-th.js" path="locale" />
  <file name="locale\ext-lang-tr.js" path="locale" />
  <file name="locale\ext-lang-ukr.js" path="locale" />
  <file name="locale\ext-lang-vn.js" path="locale" />
  <file name="locale\ext-lang-zh_CN.js" path="locale" />
  <file name="locale\ext-lang-zh_TW.js" path="locale" />
  <file name="state\CookieProvider.js" path="state" />
  <file name="state\Provider.js" path="state" />
  <file name="state\StateManager.js" path="state" />
  <file name="util\ClickRepeater.js" path="util" />
  <file name="util\CSS.js" path="util" />
  <file name="util\Date.js" path="util" />
  <file name="util\DelayedTask.js" path="util" />
  <file name="util\Format.js" path="util" />
  <file name="util\History.js" path="util" />
  <file name="util\JSON.js" path="util" />
  <file name="util\KeyMap.js" path="util" />
  <file name="util\KeyNav.js" path="util" />
  <file name="util\MixedCollection.js" path="util" />
  <file name="util\Observable.js" path="util" />
  <file name="util\TaskMgr.js" path="util" />
  <file name="util\TextMetrics.js" path="util" />
  <file name="util\XTemplate.js" path="util" />
  <file name="widgets\form\Action.js" path="widgets\form" />
  <file name="widgets\form\BasicForm.js" path="widgets\form" />
  <file name="widgets\form\Checkbox.js" path="widgets\form" />
  <file name="widgets\form\CheckboxGroup.js" path="widgets\form" />
  <file name="widgets\form\Combo.js" path="widgets\form" />
  <file name="widgets\form\DateField.js" path="widgets\form" />
  <file name="widgets\form\Field.js" path="widgets\form" />
  <file name="widgets\form\FieldSet.js" path="widgets\form" />
  <file name="widgets\form\Form.js" path="widgets\form" />
  <file name="widgets\form\Hidden.js" path="widgets\form" />
  <file name="widgets\form\HtmlEditor.js" path="widgets\form" />
  <file name="widgets\form\Label.js" path="widgets\form" />
  <file name="widgets\form\NumberField.js" path="widgets\form" />
  <file name="widgets\form\Radio.js" path="widgets\form" />
  <file name="widgets\form\RadioGroup.js" path="widgets\form" />
  <file name="widgets\form\TextArea.js" path="widgets\form" />
  <file name="widgets\form\TextField.js" path="widgets\form" />
  <file name="widgets\form\TimeField.js" path="widgets\form" />
  <file name="widgets\form\TriggerField.js" path="widgets\form" />
  <file name="widgets\form\VTypes.js" path="widgets\form" />
  <file name="widgets\grid\AbstractSelectionModel.js" path="widgets\grid" />
  <file name="widgets\grid\CellSelectionModel.js" path="widgets\grid" />
  <file name="widgets\grid\CheckboxSelectionModel.js" path="widgets\grid" />
  <file name="widgets\grid\ColumnDD.js" path="widgets\grid" />
  <file name="widgets\grid\ColumnModel.js" path="widgets\grid" />
  <file name="widgets\grid\ColumnSplitDD.js" path="widgets\grid" />
  <file name="widgets\grid\EditorGrid.js" path="widgets\grid" />
  <file name="widgets\grid\GridDD.js" path="widgets\grid" />
  <file name="widgets\grid\GridEditor.js" path="widgets\grid" />
  <file name="widgets\grid\GridPanel.js" path="widgets\grid" />
  <file name="widgets\grid\GridView.js" path="widgets\grid" />
  <file name="widgets\grid\GroupingView.js" path="widgets\grid" />
  <file name="widgets\grid\PropertyGrid.js" path="widgets\grid" />
  <file name="widgets\grid\RowNumberer.js" path="widgets\grid" />
  <file name="widgets\grid\RowSelectionModel.js" path="widgets\grid" />
  <file name="widgets\layout\AbsoluteLayout.js" path="widgets\layout" />
  <file name="widgets\layout\AccordionLayout.js" path="widgets\layout" />
  <file name="widgets\layout\AnchorLayout.js" path="widgets\layout" />
  <file name="widgets\layout\BorderLayout.js" path="widgets\layout" />
  <file name="widgets\layout\CardLayout.js" path="widgets\layout" />
  <file name="widgets\layout\ColumnLayout.js" path="widgets\layout" />
  <file name="widgets\layout\ContainerLayout.js" path="widgets\layout" />
  <file name="widgets\layout\FitLayout.js" path="widgets\layout" />
  <file name="widgets\layout\FormLayout.js" path="widgets\layout" />
  <file name="widgets\layout\TableLayout.js" path="widgets\layout" />
  <file name="widgets\menu\Adapter.js" path="widgets\menu" />
  <file name="widgets\menu\BaseItem.js" path="widgets\menu" />
  <file name="widgets\menu\CheckItem.js" path="widgets\menu" />
  <file name="widgets\menu\ColorItem.js" path="widgets\menu" />
  <file name="widgets\menu\ColorMenu.js" path="widgets\menu" />
  <file name="widgets\menu\DateItem.js" path="widgets\menu" />
  <file name="widgets\menu\DateMenu.js" path="widgets\menu" />
  <file name="widgets\menu\Item.js" path="widgets\menu" />
  <file name="widgets\menu\Menu.js" path="widgets\menu" />
  <file name="widgets\menu\MenuMgr.js" path="widgets\menu" />
  <file name="widgets\menu\Separator.js" path="widgets\menu" />
  <file name="widgets\menu\TextItem.js" path="widgets\menu" />
  <file name="widgets\tips\QuickTip.js" path="widgets\tips" />
  <file name="widgets\tips\QuickTips.js" path="widgets\tips" />
  <file name="widgets\tips\Tip.js" path="widgets\tips" />
  <file name="widgets\tips\ToolTip.js" path="widgets\tips" />
  <file name="widgets\tree\AsyncTreeNode.js" path="widgets\tree" />
  <file name="widgets\tree\TreeDragZone.js" path="widgets\tree" />
  <file name="widgets\tree\TreeDropZone.js" path="widgets\tree" />
  <file name="widgets\tree\TreeEditor.js" path="widgets\tree" />
  <file name="widgets\tree\TreeEventModel.js" path="widgets\tree" />
  <file name="widgets\tree\TreeFilter.js" path="widgets\tree" />
  <file name="widgets\tree\TreeLoader.js" path="widgets\tree" />
  <file name="widgets\tree\TreeNode.js" path="widgets\tree" />
  <file name="widgets\tree\TreeNodeUI.js" path="widgets\tree" />
  <file name="widgets\tree\TreePanel.js" path="widgets\tree" />
  <file name="widgets\tree\TreeSelectionModel.js" path="widgets\tree" />
  <file name="widgets\tree\TreeSorter.js" path="widgets\tree" />
  <file name="widgets\Action.js" path="widgets" />
  <file name="widgets\BoxComponent.js" path="widgets" />
  <file name="widgets\Button.js" path="widgets" />
  <file name="widgets\ColorPalette.js" path="widgets" />
  <file name="widgets\Component.js" path="widgets" />
  <file name="widgets\ComponentMgr.js" path="widgets" />
  <file name="widgets\Container.js" path="widgets" />
  <file name="widgets\CycleButton.js" path="widgets" />
  <file name="widgets\DataView.js" path="widgets" />
  <file name="widgets\DatePicker.js" path="widgets" />
  <file name="widgets\Editor.js" path="widgets" />
  <file name="widgets\Layer.js" path="widgets" />
  <file name="widgets\LoadMask.js" path="widgets" />
  <file name="widgets\MessageBox.js" path="widgets" />
  <file name="widgets\PagingToolbar.js" path="widgets" />
  <file name="widgets\Panel.js" path="widgets" />
  <file name="widgets\PanelDD.js" path="widgets" />
  <file name="widgets\ProgressBar.js" path="widgets" />
  <file name="widgets\Resizable.js" path="widgets" />
  <file name="widgets\Shadow.js" path="widgets" />
  <file name="widgets\Slider.js" path="widgets" />
  <file name="widgets\SplitBar.js" path="widgets" />
  <file name="widgets\SplitButton.js" path="widgets" />
  <file name="widgets\StatusBar.js" path="widgets" />
  <file name="widgets\TabPanel.js" path="widgets" />
  <file name="widgets\Toolbar.js" path="widgets" />
  <file name="widgets\Viewport.js" path="widgets" />
  <file name="widgets\Window.js" path="widgets" />
  <file name="widgets\WindowManager.js" path="widgets" />
  <file name="debug.js" path="" />
</project>