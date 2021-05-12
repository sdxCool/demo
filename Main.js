Ext.define('demo.controller.Main', {
    extend: 'Ext.app.Controller',
    requires: ['Ext.window.MessageBox'],

    stores: ["FrList", "NewFrTip"],//将store引过来，然后在view中的Main.js中可以直接使用，自动生成一个get方法

    refs: [ //会自动生成引用里的内容的get方法
        {
            ref: "newFrWin",
            selector: "newFrWin",
        },
        {
            ref: "frtipgrid",
            selector: "app-main #frTip grid",
        },
        {
            ref: "frlistgrid",
            selector: "app-main #frList grid",
        },
        {
            ref: "setting",
            selector: "SystemConfig",
        }
    ],

    init: function () {
        var me = this;
        me.control({
            "app-main #frlist #queryFrId": {
                click: function () {
                    var value = Ext.ComponentQuery.query("textfield[itemId=queryFrFieldId]")[0].getValue();
                    var store = Ext.ComponentQuery.query("[itemId=frlist] grid")[0].store;
                    if (value == "") {
                        store.proxy.url = "rest/frList";
                    } else {
                        var query = {
                            "conditions": [{ "field": "short_desc", "value": value }]
                        }
                        store.proxy.url = "rest/frList" + "?query=" + JSON.stringify(query);
                    }
                    store.load();
                }
            },
            "app-main #frTip #queryTipId": {
                click: function () {
                    var value = Ext.ComponentQuery.query("textfield[itemId=queryTipFieldId]")[0].getValue();
                    var store = Ext.ComponentQuery.query("[itemId=frTip] grid")[0].store;
                    if (value == "") {
                        store.proxy.url = "rest/frTip";
                    } else {
                        var query = {
                            "conditions": [{ "field": "bug_id", "value": value }]
                        }
                        store.proxy.url = "rest/frTip" + "?query=" + JSON.stringify(query);
                    }
                    store.load();
                }
            },
            "app-main #frTip #addBtn": {
                click: function () {
                    Ext.create('demo.view.NewFrWin', {
                        // putTest:'post',
                    }).show();
                },
            },
            "app-main #frTip #editBtn": {
                click: function () {
                    var selectedItem = this.getFrtipgrid() //拿到列表
                        .getSelectionModel() //拿到列表的选择器模型
                        .getSelection(); //拿到选择项
                    // console.log(selectedItem);
                    if (selectedItem.length == 1) {
                        var window = Ext.create('demo.view.NewFrWin');
                        window.down("form").loadRecord(selectedItem[0]);
                        window.show();
                    }
                },
            },
            "app-main #frTip #delBtn": {
                click: function () {

                    var selectedItem = me.getFrtipgrid() //拿到列表
                        .getSelectionModel() //拿到列表的选择器模型
                        .getSelection();
                    // var fr_id = selectedItem[0]["data"]["id"];//拿到FR的ID
                    if (selectedItem.length == 1) {
                        var d = selectedItem[0];
                        //"<p hidden>3</p><font color='red'>FR:"+fr_id+"</font>"
                        Ext.Msg.confirm("警告", "确定要删除\"" + d.data.description + "\"吗？", function (btnId) {
                            if (btnId == 'yes') {
                                Ext.Ajax.request({
                                    url: 'rest/frTip',
                                    method: "delete",
                                    jsonData: { id: d.data["id"] },
                                    success: function (response) {
                                        me.getFrtipgrid().getStore().reload();
                                    }
                                });
                            }
                        })
                    }
                    else {
                        Ext.Msg.alert("警告", "请选择一条数据");
                    }
                }
            },
            "newFrWin #submitBtn": {
                click: function () {
                    var values = me.getNewFrWin().down("form").getValues();
                    var store1 = Ext.create("demo.model.NewFrTip", values);
                    store1.save({
                        callback: function (record, operation, success) {
                            if (success) {
                                me.getNewFrWin().close();//关窗口
                                me.getNewFrTipStore().reload();//刷列表
                                //me.getUserStore().reload();
                            }
                        }
                    });

                },
            },
            "SystemConfig #submitBtn": {
                click: function () {
                    var values = me.getSetting().down("form").getValues();
                    var store1 = Ext.create("demo.model.Setting", values);
                    Ext.Msg.confirm("警告", "确定要更改系统设置吗？", function (btnId) {
                        if (btnId == 'yes') {
                            store1.save({
                                callback: function (record, operation, success) {
                                    if (success) {
                                        Ext.Msg.alert("提示","更改成功！");
                                    }
                                }
                            })
                        }
                    });
                }
            }

        });
    },
});
