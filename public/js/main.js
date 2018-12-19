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





let price = 0;
let weight = 0;
$('.quantity').on('change', () => {
  console.log('this works');
  $(this).addClass('urgh');
});
