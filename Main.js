
Ext.define('demo.view.Main', {
    extend: 'Ext.container.Container',
    requires: [//相当于import，引入需要的组件
        'Ext.tab.Panel',
        'Ext.layout.container.Border',
        'demo.store.FrList',
        'demo.model.FrList',
        'demo.store.NewFrTip',
        'Ext.ux.form.SearchField',
        'demo.view.SystemConfig'
    ],

    controller:'Main',
    //store:["FrList"],
    xtype: 'app-main',
    layout: {
        type: 'border' //显示的布局信息       
    },

    items: [{
        region: 'north',
        height: 50,
        xtype: "container",
        items: [
            {
                xtype: 'label',
                text: "fr和bug提醒系统",
                style: 'fontSize:30px',
            }
        ]
    }, {
        region: 'center',
        xtype: "tabpanel",
        renderTo: document.body,
        items: [
                {
                title: 'FR列表',
                itemId: 'frlist',
                items:[
                    {
                        xtype:'grid',
                        // bodyStyle :'overflow-x:invisible;overflow-y:scroll',
                        store:'FrList',
                        columns:[
                            {
                                text: "FR",
                                dataIndex: "bug_id",
                                xtype:'templatecolumn',tpl:'{id}:{description}',
                                flex:1,
                            } ,{
                                text: "FS",
                                dataIndex: "cf_webui_fs",
                                flex:0.8,
                                renderer:function(value,cellmeta,record){
                                    var index=record.data["cf_webui_fs_start"]+"~"+record.data["cf_webui_fs_end"];
                                    if(index != '~'){
                                        return index;
                                    }
                                }
                                // xtype:'templatecolumn',tpl:'{cf_webui_fs_start}:{cf_webui_fs_end}',
                            },{
                                text: "DS",
                                dataIndex: "cf_webui_ds",
                                flex:0.8,
                                renderer:function(value,cellmeta,record){
                                    var index=record.data["cf_webui_ds_start"]+"~"+record.data["cf_webui_ds_end"];
                                    if(index != '~'){
                                        return index;
                                    }
                                }
                                //xtype:'templatecolumn',tpl:'{cf_webui_ds_start}~{cf_webui_ds_end}',
                            },{
                                text: "Coding",
                                dataIndex: "cf_webui_coding",
                                flex:0.8,
                                renderer:function(value,cellmeta,record){
                                    var index=record.data["cf_webui_coding_start"]+"~"+record.data["cf_webui_coding_end"];
                                    if(index != '~'){
                                        return index;
                                    }
                                }
                               // xtype:'templatecolumn',tpl:'{cf_webui_coding_start}~{cf_webui_coding_end}',
                            },{
                                text: "Joint Debugging",
                                dataIndex: "cf_webui_jd",
                                flex:0.8,
                                renderer:function(value,cellmeta,record){
                                    var index=record.data["cf_webui_jd_start"]+"~"+record.data["cf_webui_jd_end"];
                                    if(index != '~'){
                                        return index;
                                    }
                                }
                                //xtype:'templatecolumn',tpl:'{cf_webui_jd_start}~{cf_webui_jd_end}',
                            },{
                                text: "Codreview",
                                dataIndex: "cf_webui_code_review",
                                flex:0.8,
                                renderer:function(value,cellmeta,record){
                                    var index=record.data["cf_webui_code_review_start"]+"~"+record.data["cf_webui_code_review_end"];
                                    if(index != '~'){
                                        return index;
                                    }
                                }
                                //xtype:'templatecolumn',tpl:'{cf_webui_code_review_start}~{cf_webui_code_review_end}',
                            },{
                                text: "UT start",
                                dataIndex: "cf_webui_ut",
                                flex:0.8,
                                //将两个字段值合并到同一列
                                renderer:function(value,cellmeta,record){
                                    var index=record.data["cf_webui_ut_start"]+"~"+record.data["cf_webui_ut_end"];
                                    if(index != '~'){
                                        return index;
                                    }
                                }
                                //xtype:'templatecolumn',tpl:'{cf_webui_ut_start}~{cf_webui_ut_end}',
                            },{
                                text: "Submit to QA",
                                dataIndex: "cf_webui_submit_to_qa",
                                width:100
                            },{
                                text: "Assign",
                                dataIndex: "assign",
                                width:150
                                
                            }
                        ],
                    },
                ],
                dockedItems: [
                    {
                        xtype: 'pagingtoolbar',
                        store: 'FrList',        
                        dock: 'bottom',
                        displayInfo: true,
                        displayMsg: '显示{0}-{1}条，共{2}条',
                        emptyMsg: '没有数据',
                    }],
                tbar:["->",
                            {

                                xtype:'label',
                                text:'输入FR的名称：'
                            },
                            {

                                xtype:'textfield',
                                itemId:'queryFrFieldId',
                                emptyText:'按名称查找',
                                enableKeyEvents:true,//实时监听extjs的textfield控件的键盘输入方法
                            },
                            {

                                text:'搜索',
                                itemId:'queryFrId',
                            },
                            
                        ],
                bodyStyle :'overflow-x:invisible;overflow-y:scroll',
            }, 
            {
                title: 'Bug列表',
                itemId: 'bugList'
            }, {
                title: 'Fr提醒',
                itemId: 'frTip',
                items: [{
                    xtype: 'grid',
                    store: 'NewFrTip',
                    columns: [
                        // {
                        //     xtype: "hiddenfield",
                        //     name: "id",
                        // },
                        {
                            text: "FR-ID",
                            width: 200,
                            dataIndex: "fr_id",
                        },
                        {
                            text: "FR名称",
                            width:700,
                            dataIndex: "description",
                        },
                        {
                            text: "提醒内容",
                            flex: 1,
                            dataIndex: "type",
                            renderer: function (value, cellmeta, record) {
                                return value == '1' ? 'FR开始' : 'FR提交QA';
                            }
                        },
                        {
                            text: "提前提醒天数",
                            flex: 1,
                            dataIndex: "date",
                            renderer: function (value, cellmeta, record) {
                                if (value == '1') {
                                    return '提前1天';
                                } else if (value == '2') {
                                    return '提前2天';
                                } else if (value == '3') {
                                    return '提前3天';
                                } else if (value == '4') {
                                    return '提前4天';
                                } else if (value == '5') {
                                    return '提前5天';
                                } else if (value == '7') {
                                    return '提前7天';
                                } else if (value == '9') {
                                    return '提前9天';
                                }
                            }
                        },
                    ],
                }],
                tbar:[{
                    text: "新增",
                    itemId: 'addBtn',
                },
                {
                    text: "修改",
                    itemId: 'editBtn',
                },
                {
                    text: "删除",
                    itemId: 'delBtn',
                },"->",
                        {

                            xtype:'label',
                            text:'输入FR的ID：'
                        },
                        {

                            xtype:'textfield',
                            itemId:'queryTipFieldId',
                            emptyText:'按id查找',
                            enableKeyEvents:true,//实时监听extjs的textfield控件的键盘输入方法
                        },
                        {

                            text:'搜索',
                            itemId:'queryTipId',
                        },
                        
                    ],
                dockedItems: [{
                    xtype: 'pagingtoolbar',
                    store: 'NewFrTip',
                    dock: 'bottom',//停靠的地方
                    displayInfo: true,
                    displayMsg: '显示{0}-{1}条，共{2}条',
                    emptyMsg: '没有数据',
                }],
            }, {
                title: 'Bug提醒',
                itemId: 'bugTip',
            },
            {
                title: '系统设置',
                itemId: 'systemconfig',
                xtype:"SystemConfig"
            
            }
        ]
    },
    ],
});