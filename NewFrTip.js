Ext.define('demo.model.NewFrTip',{
    extend:'Ext.data.Model',

    fields: [
        "id",
        "fr_id",
        "description",
        "type",
        "date"
    ],
    
    proxy:{
        appendId : false,
        type:"rest",
        url:"rest/frTip",//提供字符串的页面
        reader:{
            type:'json',
            root:"result",
            // totalProperty:'total'//总项数
        }
    },
});