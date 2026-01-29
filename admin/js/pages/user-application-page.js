jQuery(document).ready(function( $ ) {
    var uap_user_application_page;

    function run(){
        uap_user_application_page = new Uap_User_Application_Page($);
    }

    class Uap_User_Application_Page{
        constructor(){
            this.user_info_section_expand = $('#user-info-section-expand');
            this.user_info_section_contract = $('#user-info-section-contract');
            this.user_info_section_content = $('#user-info-section-content');

            this.household_housing_section_expand = $('#household-housing-section-expand');
            this.household_housing_section_contract = $('#household-housing-section-contract');
            this.household_housing_section = $('#household-housing-section');

            this.household_energy_section_expand = $('#household-energy-section-expand');
            this.household_energy_section_contract = $('#household-energy-section-contract');
            this.household_energy_section = $('#household-energy-section');
            
            //#region event listeners

            this.user_info_section_expand.on("click", function(){
                uap_user_application_page.open_user_info();
            });

            this.user_info_section_contract.on("click", function(){
                uap_user_application_page.close_user_info();
            });

            this.household_housing_section_expand.on("click", function(){
                uap_user_application_page.open_housing();
            });

            this.household_housing_section_contract.on("click", function(){
                uap_user_application_page.close_housing();
            });

            this.household_energy_section_expand.on("click", function(){
                uap_user_application_page.open_energy();
            });

            this.household_energy_section_contract.on("click", function(){
                uap_user_application_page.close_energy();
            });

            $('.section-expand').on("click", function(){
                console.log($(this).attr("id"));
                console.log($(this).parents(".application-section"));

                if ($(this).attr("id").includes("-expand")){
                    var section = $(this).parents(".application-section");
                    console.log(section.find('input[type="radio"]:first-child').attr("id"));
                    section.find('input[type="radio"]:first-child').click();
                }
            });

            $('.oym-tabs input[type="radio"] ').on("click", function(){
                console.log($(this).attr("id"));
                $("#user-" + $(this).attr("id")).click();;
            });


            //#endregion
        }
    
        open_user_info(){
            this.user_info_section_content.removeClass("oym-hidden");
            this.user_info_section_expand.addClass("oym-hidden");
            this.user_info_section_contract.removeClass("oym-hidden");
        }

        close_user_info(){
            this.user_info_section_content.addClass("oym-hidden");
            this.user_info_section_expand.removeClass("oym-hidden");
            this.user_info_section_contract.addClass("oym-hidden");
        }

        open_housing(){
            this.household_housing_section.removeClass("oym-hidden");
            this.household_housing_section_expand.addClass("oym-hidden");
            this.household_housing_section_contract.removeClass("oym-hidden");
        }

        close_housing(){
            this.household_housing_section.addClass("oym-hidden");
            this.household_housing_section_expand.removeClass("oym-hidden");
            this.household_housing_section_contract.addClass("oym-hidden");
        }

        open_energy(){
            this.household_energy_section.removeClass("oym-hidden");
            this.household_energy_section_expand.addClass("oym-hidden");
            this.household_energy_section_contract.removeClass("oym-hidden");
        }

        close_energy(){
            this.household_energy_section.addClass("oym-hidden");
            this.household_energy_section_expand.removeClass("oym-hidden");
            this.household_energy_section_contract.addClass("oym-hidden");
        }

    }

    run();
});

