const sweets = document.getElementById('sweets');

if (sweets) {
  sweets.addEventListener('click', e => {
    if (e.target.className === 'btn btn-danger delete-sweet') {
      if (confirm('Are you sure?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/media/public/sweet/delete/${id}`, {
          method: 'DELETE'
        }).then(res => window.location.reload());
      }
    }
  });
}


$.noConflict();
jQuery(document).ready(function ($) {

    let totalPrice = 0;
    let totalWeight = 0;
    let totalQuantity = 0;
    let values = [];


    let inputs = $('.quantity');
    for (let i =0; i < inputs.length; i++) {
        var quantity = $(inputs[i]).val();
        values.push({'id' : $(inputs[i]).attr('id'), 'quantity' : quantity});
    }

    $('.quantity').change(function() {

        let quantity = parseFloat($(this).val());
        let weight = $(this).siblings('.weight').children().html();
        let price = $(this).siblings('.price').children().html();
        let id = $(this).attr('id');

        var oldQuantity = values[values.findIndex(x => x.id === id )].quantity;

        if (quantity > oldQuantity) {
            let oldQuantity = quantity - 1;
            totalWeight -= oldQuantity * parseFloat(weight);
            totalQuantity -= oldQuantity;
            totalPrice -= (parseFloat(price) * parseFloat(weight)) * oldQuantity;
        } else {
            let oldQuantity = quantity + 1;
            totalWeight -= oldQuantity * parseFloat(weight);
            totalQuantity -= oldQuantity;
            totalPrice -= (parseFloat(price) * parseFloat(weight)) * oldQuantity;
        }

        totalWeight += quantity * parseFloat(weight);
        totalQuantity += quantity;
        totalPrice += (parseFloat(price) * parseFloat(weight)) * quantity;

        values[values.findIndex(x => x.id === id )].quantity = quantity;

        console.log(totalWeight);
        console.log(totalQuantity);
        console.log(totalPrice);

        $('.total-weight').html('Total weight = ' + totalWeight + ' g');
        $('.total-sweets').html('Total No. sweet = ' + totalQuantity);
        $('.total-cost').html('Total cost = £'+ totalPrice);

        if (totalWeight >= 40) {
            $('#checkout').removeAttr('disabled');
        } else {
            $('#checkout').attr('disabled', true);
        }
    });


});
