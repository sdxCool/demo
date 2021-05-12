Ext.define("demo.store.NewFrTip",{
    extend: "Ext.data.Store",
    model:"demo.model.NewFrTip",
    pageSize:38,//确定每页显示的数据条数
    autoLoad:{start:0,limit:38},
});