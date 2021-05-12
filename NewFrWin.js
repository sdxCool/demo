Ext.define("demo.view.NewFrWin",{
    extend :"Ext.window.Window",
    xtype :"newFrWin",
    //putTest:"put",
    title:"Fr提醒",
    initComponent :function(){
        var me = this;
        frContentStore = Ext.create("Ext.data.Store",{
            fields:['idField','content'],
            data:[
                {idField:'1',content:"FR开始"},
                {idField:'2',content:"FR提交QA"}
            ]
        })
        frDataStore = Ext.create("Ext.data.Store",{
            fields:['idField','content'],
            data:[
                {idField:'1',content:"提前1天"},
                {idField:'2',content:"提前2天"},
                {idField:'3',content:"提前3天"},
                {idField:'4',content:"提前4天"},
                {idField:'5',content:"提前5天"},
                {idField:'7',content:"提前7天"},
                {idField:'9',content:"提前9天"},
            ]
        })
        me.formItems=[
            {
                xtype:"combobox",
                itemId:"fr_name_combo",
                fieldLabel:'Fr名称',
                name:'fr_id',
                autoSelect: true,
                queryMode:'local',
                store:Ext.create('demo.store.FrList',{
                    pageSize:10000, autoLoad:{start:0,limit:100000}
                }),
                editable:false,//是否可编辑
                displayField:"description",//显示的字段
                valueField:"id",//值的字段
            },
            {
                xtype:"combobox",
                fieldLabel:'提醒内容',
                name:'type',//form提交时的名字
                store:frContentStore,
                editable:false,//是否可编辑
                displayField:"content",//显示的字段
                valueField:"idField",//值的字段
                //emptyText:"--FR开始或FR提交QA--",//没有值时的水印
                queryMode:"local",//查询模式
                value:'1',
            },
            {
                xtype:"combobox",
                fieldLabel:'天数',
                autoSelect:true,
                name:'date',
                itemId:"fr_name_combo1",
                // store:frDataStore,

                store: Ext.create("Ext.data.Store",{
                    fields:['idField','content'],
                    data:[
                        {idField:'1',content:"提前1天"},
                        {idField:'2',content:"提前2天"},
                        {idField:'3',content:"提前3天"},
                        {idField:'4',content:"提前4天"},
                        {idField:'5',content:"提前5天"},
                        {idField:'7',content:"提前7天"},
                        {idField:'9',content:"提前9天"},
                    ]
                }),
                value:"3",
                editable:false,//是否可编辑
                displayField:"content",//显示的字段
                valueField:"idField",//值的字段
                //emptyText:"--提前提醒天数--",//没有值时的水印
                queryMode:"local",//查询模式
                
            },
            {
                xtype:"hiddenfield",
                name:"id",
            }
        ];

        me.items = [{
            xtype:"form",
            bodyPadding:15,
            
            items:me.formItems,
        }];

        me.bbar = ['->',{
            text:'确定',
            itemId :"submitBtn",
        },{
            text:'取消',
            handler:function(){
                me.close();
            },
        },"->"]
        me.callParent();
    }
})