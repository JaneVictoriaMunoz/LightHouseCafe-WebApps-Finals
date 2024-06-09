function enlargeImage(img) {
    var modal = document.getElementById('modal');
    var modalImg = document.getElementById('modal-image');
    modal.style.display = 'block';
    modalImg.src = img.src;
}

function closeModal() {
    var modal = document.getElementById('modal');
    modal.style.display = 'none';
}

// Close the modal if the user clicks outside of the image
window.onclick = function(event) {
    var modal = document.getElementById('modal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
// First Quantity
		$(document).ready(function() {
			$(".add-to-cart").click(function(e) {
				e.preventDefault();
				var id = $(this).data('id');
				var name = $(this).data('name');
				var price = $(this).data('price');

				$.ajax({
					url: 'ATC.php',
					method: 'POST',
					data: { add_to_cart: true, id: id, name: name, price: price },
					success: function(response) {
						var cartCount = parseInt($("#cart-count").text());
						$("#cart-count").text(cartCount + 1);
					}
				});
			});
		});