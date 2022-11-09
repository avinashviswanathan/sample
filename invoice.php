<?php
// Invoice Generator 
?>

<html>
	<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script>
		// For adding new item
		$(document).on('click', '.add-item', function() {
			var element = document.getElementsByClassName("cls-item-set");
			var id = $(this).closest('.wrapper').find('.cls-item-set:last').data('index');
			var newId = parseInt(id) + 1;
			var cloneSection = $(this).closest('.wrapper').find('.cls-item-set:last').clone();
			var insertSection = $(this).closest('.wrapper').find('.cls-item-set:last');
			cloneSection.prop('id', 'itemSet_' + newId);
			cloneSection.attr('data-index', newId);
			cloneSection.find('.txtName').prop('name', 'txtName-' + newId);
			cloneSection.find('.txtName').prop('id', 'txtName-' + newId);
			cloneSection.find('.txtName').prop('value', '');
			cloneSection.find('.txtQuantity').prop('name', 'txtQuantity-' + newId);
			cloneSection.find('.txtQuantity').prop('id', 'txtQuantity-' + newId);
			cloneSection.find('.txtQuantity').prop('value', '');
			cloneSection.find('.txtUnitPrice').prop('name', 'txtUnitPrice-' + newId);
			cloneSection.find('.txtUnitPrice').prop('id', 'txtUnitPrice-' + newId);
			cloneSection.find('.txtUnitPrice').prop('value', '');
			cloneSection.find('.slctTax').prop('name', 'slctTax-' + newId);
			cloneSection.find('.slctTax').prop('id', 'slctTax-' + newId);
			cloneSection.find('.slctTax').prop('value', '0');
			cloneSection.find('.divLineTotal').prop('id', 'divLineTotal-' + newId);
			cloneSection.find('.divLineTotal').html('0');
			cloneSection.find('.hidLineTotal').prop('name', 'hidLineTotal-' + newId);
			cloneSection.find('.hidLineTotal').prop('id', 'hidLineTotal-' + newId);
			cloneSection.find('.hidLineTotal').prop('value', '0');
			cloneSection.find('.hidLineTotalWOTax').prop('name', 'hidLineTotalWOTax-' + newId);
			cloneSection.find('.hidLineTotalWOTax').prop('id', 'hidLineTotalWOTax-' + newId);
			cloneSection.find('.hidLineTotalWOTax').prop('value', '0');
			insertSection.after(cloneSection);
		});

		// For deleting the item
		$(document).on('click', '.rmvRow', function() {
			var element = document.getElementsByClassName("cls-item-set");
			var id = $(this).closest('.cls-item-set').data('index');
			$('#itemSet_' + id).remove();
			calculateSubTotal();
		});

		// Function to calculate line item
		function calculateLineTotal(element) {
			var lineTotal = 0;
			var lineTotalWithOutTax = 0;
			var id = element.getAttribute('id').split('-')[1];

			var quantinty = document.getElementById('txtQuantity-' + id).value;
			var unitPrice = document.getElementById('txtUnitPrice-' + id).value;
			var tax = document.getElementById('slctTax-' + id).value;
			if (quantinty != '') {
				quantinty = parseInt(quantinty);
			} else {
				quantinty = 0;
			}
			if (unitPrice != '') {
				unitPrice = parseFloat(unitPrice);
			} else {
				unitPrice = 0;
			}
			lineTotalWithOutTax = unitPrice * quantinty;
			var lineTax = (lineTotalWithOutTax * tax) / 100;
			lineTotal = lineTotalWithOutTax + lineTax;
			document.getElementById('divLineTotal-' + id).innerHTML = lineTotal;
			document.getElementById('hidLineTotal-' + id).value = lineTotal;
			document.getElementById('hidLineTotalWOTax-' + id).value = lineTotalWithOutTax;
			calculateSubTotal();
		}

		// Function to calculate Subtotal 
		function calculateSubTotal(){
			var subTotalWithTax = 0;
			var lineSubTotalWithTax = 0;
			var subTotalWithOutTax = 0;
			var lineSubTotalWithOutTax = 0;
			var id = '';
			$('.invoice-table').find('.cls-item-set').each(function(){
				id = $(this).data('index');
				if(document.getElementById("hidLineTotal-"+id)){
					lineSubTotalWithTax = lineSubTotalWithTax + parseFloat(document.getElementById("hidLineTotal-"+id).value);
				}
				if(document.getElementById("hidLineTotalWOTax-"+id)){
					lineSubTotalWithOutTax = lineSubTotalWithOutTax + parseFloat(document.getElementById("hidLineTotalWOTax-"+id).value);
				}
			});
			subTotalWithTax = lineSubTotalWithTax;
			subTotalWithOutTax = lineSubTotalWithOutTax;
			
			if(document.getElementById("spanSubTotalWT")){
				document.getElementById("spanSubTotalWT").innerHTML = subTotalWithTax;
				document.getElementById("hidSubTotalWT").value = subTotalWithTax;
			}
			if(document.getElementById("spanSubTotalWOT")){
				document.getElementById("spanSubTotalWOT").innerHTML = subTotalWithOutTax;
				document.getElementById("hidSubTotalWOT").value = subTotalWithOutTax;

			}
			calculateTotal(subTotalWithTax);
		}

		// Function to calculate total 
		function calculateTotal(subTotalWithTax){
			var total = 0;
			if(document.getElementById("slctDiscountType") && document.getElementById("txtdiscount")){
				var type = document.getElementById("slctDiscountType").value;
				subTotalWithTax = parseFloat(subTotalWithTax);
				var discount = document.getElementById("txtdiscount").value;
				if(type == 'amount'){
					console.log(subTotalWithTax);
					console.log(discount);
					total = subTotalWithTax - discount;
					console.log(type);
				}
				else{
					total = subTotalWithTax - ((subTotalWithTax * discount )/100);
				}
			}
			if(document.getElementById("spanTotal")){
				document.getElementById("spanTotal").innerHTML = total;
				document.getElementById("hidTotal").value = total;
			}
			
		}

		// Function for submitting the form 
		function submitForm(){
			var objForm = document.getElementById('invoiceGenarator');
			var id = $('.wrapper').find('.cls-item-set:last').data('index');
			if(document.getElementById("hidLength")){
				document.getElementById("hidLength").value = id;
			}
			objForm.submit();
		}
	</script>
	</head>
<body>
<form name="invoiceGenarator" id="invoiceGenarator" method="POST" action="printInvoice.php">
	<div class="wrapper" style="width: 60%; margin: auto;">
		<div style="text-align: right;margin-bottom: 23px;margin-top: 24px;">
			<button type="button" onclick="submitForm()">Generate Invoice</button>
			<button type="button" class="add-item"> Add Item</button>
			<input type="hidden" name="hidLength" id="hidLength" value="0">
		</div>
		<div>
			<table class="invoice-table" style="float: right;">
				<tr class="header">
					<td>
						<label>
							Name
						</label>
					</td>
					<td>
						<label>
							Quantity
						</label>
					</td>
					<td>
						<label>
							Unit Price
						</label>
					</td>
					<td>
						<label>
							Tax
						</label>
					</td>
					<td>
						<label>
							Total
						</label>
					</td>
				</tr>
				<tr class="body cls-item-set" data-index="1" id="itemSet_1">
					<td>
						<div>
							<input class="txtName" name="txtName-1" id="txtName-1" value="">
						</div>
					</td>
					<td>
						<div>
							<input class="txtQuantity" name="txtQuantity-1" id="txtQuantity-1" onchange="calculateLineTotal(this)" value="">
						</div>
					</td>
					<td>
						<div>
							<input class="txtUnitPrice" name="txtUnitPrice-1" id="txtUnitPrice-1" onchange="calculateLineTotal(this)" value="">
						</div>
					</td>
					<td>
						<div>
							<select class="slctTax" name="slctTax-1" id="slctTax-1" onchange="calculateLineTotal(this)">
								<option value="0">0 %</option>
								<option value="1">1 %</option>
								<option value="5">5 %</option>
								<option value="10">10 %</option>
							</select>
						</div>
					</td>
					<td>
						<div class="divLineTotal" id="divLineTotal-1">
							0
						</div>
						<input type="hidden" name="hidLineTotal-1" class="hidLineTotal" id="hidLineTotal-1" value="0">
						<input type="hidden" name="hidLineTotalWOTax-1" class="hidLineTotalWOTax" id="hidLineTotalWOTax-1" value="0">
					</td>
					<td>
						<button type="button" class="rmvRow">-</button>
					</td>
				</tr>
				<tr>
					<td colspan="1">&nbsp</td>
					<td colspan="3">
						<div>
							<label>Discount Type</label>
							<select name="slctDiscountType" id="slctDiscountType" onchange="calculateSubTotal()">
								<option value="amount">Amount</option>
								<option value="percentage">Percentage</option>
							</select>
							<label>value</label>
							<input type="text" name="txtdiscount" id="txtdiscount" value="0" onchange="calculateSubTotal()">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp</td>
					<td colspan="2">
						<div>
							<label>Sub Total (with out tax):</label><span id="spanSubTotalWOT"> 0</span>
							<input type="hidden" name="hidSubTotalWOT" class="hidSubTotalWOT" id="hidSubTotalWOT" value="0">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp</td>
					<td colspan="2">
						<div>
							<label>Sub Total (with tax): </label><span id="spanSubTotalWT"> 0</span>
							<input type="hidden" name="hidSubTotalWT" class="hidSubTotalWT" id="hidSubTotalWT" value="0">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp</td>
					<td colspan="2">
						<div>
							<label> Total: </label><span id="spanTotal"> 0</span>
							<input type="hidden" name="hidTotal" class="hidTotal" id="hidTotal" value="0">
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>
</body>
</html>