jQuery(document).ready(function( $ ) {
    var uap_recent_user_applications_page;

    function run(){
        uap_recent_user_applications_page = new Uap_Recent_User_Application_Page($);
    }

    class Uap_Recent_User_Application_Page{
        constructor(){
            this.user_application_start_date = '';
            this.user_application_end_date = '';
            this.user_application_start_date_filter = $('#user-application-start-date-filter');
            this.user_application_end_date_filter = $('#user-application-end-date-filter');
            
            //#region tabulator table
            this.deleteIcon = function(cell, formatterParams, onRendered){ //plain text value
                return uap_recent_user_applications_page.getIcon('minus');
            };

            this.application_link = function(cell, formatterParams, onRendered){ //plain text value
                var row = cell.getRow(); 
                var user_application_guid =  row.getData().user_application_guid;
                var application_id = cell.getValue();
                //var user_application_guid = cell.getValue();
                var application_url = oymuap_js_defaults.admin_url + "admin.php?page=oymuap-user-application&user_application_guid=" + user_application_guid;
                return "<a href='" + application_url + "'>" + application_id + "</a>";
            };

            this.user_link = function(cell, formatterParams, onRendered){ //plain text value
                var user_id = cell.getValue();
                var application_url = oymuap_js_defaults.admin_url + "admin.php?page=oymuap-user-applications&user_id=" + user_id;
                return "<a href='" + application_url + "'>" + user_id + "</a>";
            };

            this.user_applications_table = new Tabulator("#user-applications-table", {
                data:table_data,
                ajaxURL:oymuap_js_defaults.ajax_url, //ajax URL
                ajaxConfig:"POST", //ajax HTTP request type
                ajaxParams: function(){
                    return {action:"get_user_applications_by_range_json", security:oymuap_js_defaults.security, 
                        start_date:uap_recent_user_applications_page.user_application_start_date,
                        end_date:uap_recent_user_applications_page.user_application_end_date,
                    };
                },
                index: "id",
                movableRows:false, 
                layout:"fitColumns",
                pagination:"local",
                paginationSize:20,
                paginationSizeSelector:[10, 20, 50],
                paginationCounter:"rows",
                columns:[ //set column definitions for imported table data
                {title:"User ID", field:"user_id", width:100, sorter:"string", formatter:this.user_link, headerFilter:true},
                {title:"Application ID", field:"user_application_id", width:140, sorter:"string", formatter:this.application_link, headerFilter:true},
                {title:"Application GUID", field:"user_application_guid", width:160, sorter:"string", headerFilter:true},
                {title:"Date", field:"application_date", width:180, sorter:"string", headerFilter:false},
                {title:"Name", field:"name", sorter:"string", headerFilter:true},
                {title:"Email", field:"email", sorter:"string", formatter:"html", headerFilter:true},
                {title:"Phone", field:"phone", sorter:"string", headerFilter:true},
                {formatter:this.deleteIcon, width:30, hozAlign:"center", headerSort:false, cellClick:function(e, cell){uap_recent_user_applications_page.deleteUserApplicationCheck(cell)}},
            ],
            });

            this.user_applications_table.on("tableBuilt", function(){
                uap_recent_user_applications_page.set_date_values();
            });


            //#endregion
  
            //#region event listeners
     
            if (sessionStorage.getItem('uap_start_date_filter')){
                var startDate = new Date(sessionStorage.getItem('uap_start_date_filter'))
            } else {
                var currentDate = new Date();
                var startDate = new Date(currentDate.getTime() - 7 * 24 * 60 * 60 * 1000); 
            }
            this.user_application_start_date_filter
                .datepicker({
                    dateFormat: "mm/dd/yy",
                    defaultDate: startDate
            })
            .on( "change", function() {
                uap_recent_user_applications_page.set_date_values();
            });
            this.user_application_start_date_filter.datepicker( "setDate", startDate );
      

            this.user_application_end_date_filter
                .datepicker({
                    dateFormat: "mm/dd/yy",
                    defaultDate: new Date()
            })
            .on( "change", function() {
                uap_recent_user_applications_page.set_date_values();
            });
            this.user_application_end_date_filter.datepicker( "setDate", new Date() );

            $("form").on("submit", function (e) {
                e.preventDefault();
            });

            //#endregion
        }

        deleteUserApplicationCheck(cell) {
            var selected_row = cell.getRow(); 
            this.deleteUserAppliction(selected_row.getData().user_application_guid);
            uap_recent_user_applications_page.user_applications_table.deleteRow(selected_row);
        }

        async deleteUserAppliction(user_application_guid) {
            const result = $.ajax({
                url: oymuap_js_defaults.ajax_url,
                type: 'POST',
                data: {
                    action: "delete_user_application_by_guid",
                    security: oymuap_js_defaults.security,
                    user_application_guid: user_application_guid   
                }
            });   
            return result;   
        }

        async set_date_values(){
            var dateFormat = "mm/dd/yy";

            var start_date = $.datepicker.parseDate( dateFormat, this.user_application_start_date_filter.val());
            var end_date = $.datepicker.parseDate( dateFormat, this.user_application_end_date_filter.val());
            sessionStorage.setItem('uap_start_date_filter', start_date);
            sessionStorage.setItem('uap_end_date_filter',  end_date);
            uap_recent_user_applications_page.user_application_end_date_filter.datepicker( "option", "minDate", start_date );
            uap_recent_user_applications_page.user_application_start_date_filter.datepicker( "option", "maxDate", end_date );

            var start_date_obj = new Date(start_date);
            this.user_application_start_date = start_date_obj.toLocaleDateString();

            var end_date_obj = new Date(end_date);
            this.user_application_end_date = end_date_obj.toLocaleDateString();

            uap_recent_user_applications_page.user_applications_table.setData();
            return;
    
        }

    
        load_table(){
            this.selected_year_id = $('#uap-application-year').val();
            console.log(oymuap_js_defaults.ajax_url);
            this.user_applications_table.setData();
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

