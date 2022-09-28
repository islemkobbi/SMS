function getprice(element) {
            var stock = element.value

            $.ajax({
                url: 'php/get_price.php',
                type: 'POST',
                data: {
                    stock: stock
                },
                success: function(php_result) {
                    console.log(php_result);
                    $("#price").attr("placeholder", php_result);
                }
            })
        }