jQuery(document).ready(function($) {
    if ($('#wep-total-price-amount').length > 0) {
        var wep_total_price_amount = parseFloat($('#wep-total-price-amount').text()); // Parse the initial value as a float
        var extra_item_1_clicked = false;
        var extra_item_2_clicked = false;
        var extra_item_3_clicked = false;
        var accessories_1_clicked = false;
        var accessories_2_clicked = false;

        // Function to update variable and update the total price
        function updateCheckboxStatus(checkbox, variable, price) {
            checkbox.on('change', function() {
                variable = $(this).prop('checked');

                // Update the total price based on the checkbox state
                if (variable) {
                    wep_total_price_amount += price;
                } else {
                    wep_total_price_amount -= price;
                }

                // Update the display of the total price
                $('#wep-total-price-amount').text(wep_total_price_amount.toFixed(2));
            });
        }

        // Update status for Extra Items
        updateCheckboxStatus($('[name="extra_items[]"][value="extra_item_1"]'), extra_item_1_clicked, 2);
        updateCheckboxStatus($('[name="extra_items[]"][value="extra_item_2"]'), extra_item_2_clicked, 4);
        updateCheckboxStatus($('[name="extra_items[]"][value="extra_item_3"]'), extra_item_3_clicked, 5);

        // Update status for Accessories
        updateCheckboxStatus($('[name="accessories[]"][value="long_cable"]'), accessories_1_clicked, 55);
        updateCheckboxStatus($('[name="accessories[]"][value="type_c_cable"]'), accessories_2_clicked, 96);
    }
});
