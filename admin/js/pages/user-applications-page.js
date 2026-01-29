jQuery(document).ready(function( $ ) {
    var uap_applications_page;

    function run(){
        uap_applications_page = new Uap_Appplications_Page($);
    }

    class Uap_Appplications_Page{
        constructor(){
            this.uap_application_year_select = $('#uap-application-year');
            this.uap_application_start_date = $('#uap-application-start-date');
            this.uap_application_end_date = $('#uap-application-end-date');
            this.uap_application_search_button = $("#cac-search-applications-button");
            this.user_id = uap_user_id;
    
            
            //#region tabulator table
            this.deleteIcon = function(cell, formatterParams, onRendered){ //plain text value
                return uap_applications_page.getIcon('minus');
            };

            this.application_link = function(cell, formatterParams, onRendered){ //plain text value
                var application_id = cell.getValue();
                var current_row = cell.getRow();
                var user_application_guid = current_row.getData().user_application_guid;
                var applications_url = oymuap_js_defaults.admin_url + "admin.php?page=oymuap-user-application&application_id=" + application_id + "&user_application_guid=" + user_application_guid + "&user_id=" + uap_user_id;
                return "<a href='" + applications_url + "'>" + application_id + "</a>";
            };

            this.applications_table = new Tabulator("#applications_table", {
                data:table_data,
                ajaxURL:oymuap_js_defaults.ajax_url, //ajax URL
                ajaxConfig:"POST", //ajax HTTP request type
                ajaxParams: function(){
                    return {action:"get_user_applications_by_user_json", security:oymuap_js_defaults.security, selected_user_id:uap_applications_page.user_id};
                },
                index: "id",
                movableRows:false, 
                layout:"fitColumns",
                pagination:"local",
                paginationSize:10,
                paginationSizeSelector:[3, 6, 8, 10],
                paginationCounter:"rows",
                columns:[ //set column definitions for imported table data
                {title:"ID", field:"id", sorter:"string", formatter:this.application_link,  width:120},
                {title:"Date", field:"application_date", sorter:"string", headerFilter:true},
                {formatter:this.deleteIcon, width:30, hozAlign:"center", headerSort:false, cellClick:function(e, cell){uap_applications_page.deleteUserApplicationCheck(cell)}},
                {title:"User Application GUID", field:"user_application_guid",visible:false},
            ],
            });

            this.applications_table.on("tableBuilt", function(){
                uap_applications_page.load_table();
            });



            //#endregion
  
            //#region event listeners
     
            this.uap_application_year_select.on("change", function(){
                console.log($('#uap-application-year'));
            });


            $("form").on("submit", function (e) {
                e.preventDefault();
            });

            //#endregion
        }

        deleteUserApplicationCheck(cell) {
            var selected_row = cell.getRow(); 
            this.deleteUserAppliction(selected_row.getData().user_application_guid);
            uap_applications_page.applications_table.deleteRow(selected_row);
        }

        async deleteUserAppliction(user_application_guid) {
            const result = $.ajax({
                url: oymuap_js_defaults.ajax_url,
                type: 'POST',
                data: {
                    action: "delete_uap_user_application_by_guid",
                    security: oymuap_js_defaults.security,
                    user_application_guid: user_application_guid   
                }
            });   
            return result;   
        }
    
        load_table(){
            this.applications_table.setData();
        }

        getIcon(type){
            var icon_name;
            switch (type) {
                case 'plus':
                    icon_name = "dashicons-plus";
                    break;
                case 'minus':
                    icon_name = "dashicons-minus";
                    break;
                case 'yes':
                    icon_name = "dashicons-yes";
                    break;
                case 'no':
                    icon_name = "dashicons-no";
                    break;
                case 'edit':
                    icon_name = "dashicons-edit-page";
                    break;
                case 'view':
                    icon_name = "dashicons-welcome-view-site";
                    break;
                case 'trash':
                    icon_name = "dashicons-trash";
                    break;
                case 'selected':
                    icon_name = "dashicons-yes-alt";
                    break;
                case 'unselected':
                    icon_name = "dashicons-marker";
                    break;
                case 'flag':
                    icon_name = "dashicons-flag";
                    break;
                case 'flag':
                    icon_name = "dashicons-flag";
                    break;
                case 'empty':
                    icon_name = "";
                    break;
                default:
                    icon_name = "";
            }
        
            var icon = "<span class='oym-dash-icons dashicons " + icon_name + "'></span>";
            return icon;
        }
    }

    run();
});

