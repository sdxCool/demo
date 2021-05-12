Ext.define('demo.model.FrList',{
    extend:'Ext.data.Model',

    fields: [
        "id","description",
        "cf_webui_fs_start","cf_webui_fs_end",
        "cf_webui_ds_start","cf_webui_ds_end",
        "cf_webui_coding_start","cf_webui_coding_end",
        "cf_webui_jd_start","cf_webui_jd_end",
        "cf_webui_code_review_start","cf_webui_code_review_end",
        "cf_webui_ut_start","cf_webui_ut_end",
        "cf_webui_submit_to_qa","assign"
    ],
    proxy:{
        appendId : false,
        type:"rest",
        url:"rest/frList",//提供字符串的页面
        reader:{
            type:'json',
            root:"result",
            // totalProperty:'total'//总项数
        }
    },

    // proxy:{
    //     type: "rest",  //这是服务器端代理
    //     // url :"php/user.php",
    //     appendId:false,
    //     url:"php/fr.php",
    //    reader:{
    //        type:"json",
    //        root:"result",
    //    },
    // },

    //数据代理是进行数据读写的主要途径，通过代理操作数据进行CRUD---增删改查
    // proxy:{
    //     type: "rest",  //这是服务器端代理
    //     // url :"php/user.php",
    //     appendId:false,
    //     url:"rest/user",
    //    reader:{
    //        type:"json",
    //        root:"result",
    //    },
    // },
});