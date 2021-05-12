Ext.define("demo.store.FrList",{
    extend: "Ext.data.Store",
    model:"demo.model.FrList",
    pageSize:50,//确定每页显示的数据条数
    autoLoad:{start:0,limit:50},
});