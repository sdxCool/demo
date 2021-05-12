Ext.define("demo.view.SystemConfig", {
    extend: 'Ext.form.Panel',
    xtype: 'SystemConfig',
    initComponent: function () {
        var me = this;
        frDataStore = Ext.create("Ext.data.Store", {
            fields: ['id', 'content'],
            data: [
                { id: '1', content: "提前1天" },
                { id: '2', content: "提前2天" },
                { id: '3', content: "提前3天" },
                { id: '4', content: "提前4天" },
                { id: '5', content: "提前5天" },
                { id: '7', content: "提前7天" },
                { id: '9', content: "提前9天" },
            ]
        }),
            bugDataStore = Ext.create("Ext.data.Store", {
                fields: ['id', 'content'],
                data: [
                    { id: '1', content: "超过1天" },
                    { id: '2', content: "超过2天" },
                    { id: '3', content: "超过3天" },
                    { id: '4', content: "超过4天" },
                    { id: '5', content: "超过5天" },
                    { id: '7', content: "超过7天" },
                    { id: '9', content: "超过9天" },
                ]
            }),
            me.formItems = [

                {
                    xtype: 'radiogroup',
                    itemId: 'frtipconfig',
                    width: 300,
                    fieldLabel: 'fr提醒设置',
                    items: [{
                        columnWidth: .4,
                        name: 'fr_remind',
                        inputValue: '1',
                        boxLabel: '开启',
                        checked: true,


                    }, {
                        columnWidth: .6,
                        name: 'fr_remind',
                        inputValue: '0',
                        boxLabel: '关闭',

                    }]
                },

                {
                    xtype: "combobox",
                    fieldLabel: 'fr开始提前提醒天数',
                    labelWidth: 200,
                    name: 'fr_start_days',//form提交时的名字
                    store: frDataStore,
                    displayField: "content",//显示的字段
                    valueField: "id",//值的字段
                    //emptyText:"--FR开始或FR提交QA--",//没有值时的水印
                    queryMode: "local",//查询模式
                    value: '3',
                },
                {
                    xtype: "combobox",
                    fieldLabel: 'fr提交QA提前提醒天数',
                    labelWidth: 200,
                    name: 'fr_toqa_days',//form提交时的名字
                    store: frDataStore,
                    displayField: "content",//显示的字段
                    valueField: "id",//值的字段
                    //emptyText:"--FR开始或FR提交QA--",//没有值时的水印
                    queryMode: "local",//查询模式
                    value: '3',
                },
                {
                    xtype: 'radiogroup',
                    itemId: 'bugconfig',
                    width: 300,
                    fieldLabel: 'bug提醒设置',
                    items: [{
                        columnWidth: .4,
                        name: 'bug_remind',
                        inputValue: '1',
                        boxLabel: '开启',
                        checked: true,
                    }, {
                        columnWidth: .6,
                        name: 'bug_remind',
                        inputValue: '0',
                        boxLabel: '关闭',

                    }]
                },

                {
                    xtype: "combobox",
                    fieldLabel: 'bug超时提醒天数',
                    labelWidth: 200,
                    name: 'bug_start_days',//form提交时的名字
                    store: bugDataStore,
                    displayField: "content",//显示的字段
                    valueField: "id",//值的字段
                    //emptyText:"--FR开始或FR提交QA--",//没有值时的水印
                    queryMode: "local",//查询模式
                    value: '3',
                },
                {
                    xtype: "combobox",
                    fieldLabel: 'bug截止日期提前提醒天数',
                    labelWidth: 200,
                    name: 'bug_toqa_days',//form提交时的名字
                    store: frDataStore,
                    displayField: "content",//显示的字段
                    valueField: "id",//值的字段
                    //emptyText:"--FR开始或FR提交QA--",//没有值时的水印
                    queryMode: "local",//查询模式
                    value: '3',
                }, {
                        style:'margin:0 0 0 700px',
                        width:80,
                        xtype: "button",
                        text: '确定',
                        itemId: "submitBtn",
                    
                    
                },
                // me.tbar = [ {
                //     text: '确定',
                //     itemId: "submitBtn",
                // }, 
                //     ]
            ];

        me.items = [{
            xtype: "form",
            items: me.formItems,
        }];


        me.callParent();
    }
});