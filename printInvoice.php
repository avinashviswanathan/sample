<?php

?>
<html>

<head>
<style>
table, th, td {
  border: 1px solid black;
}
</style>
	<script>
		function printData() {
			var divToPrint = document.getElementById("printTable");
			newWin = window.open("");
			newWin.document.write(divToPrint.outerHTML);
			newWin.print();
			newWin.close();
		}
	</script>
</head>

<body><?php
	if(isset($_POST['hidLength'])){
		$totalRec = $_POST['hidLength'];
	?><div class="wrapper" style="width: 60%; margin: auto;">
		<div style="text-align: right;margin-bottom: 23px;margin-top: 24px;">
			<button type="button" onclick="printData()">Print</button>
		</div>
		<div>
			<?php 

			?>
			<table class="invoice-table" style="width:100%" id='printTable'>
				<tr class="header" >
					<td>
						<label> Name </label>
					</td>
					<td>
						<label>Quantity </label>
					</td>
					<td>
						<label>Unit Price</label>
					</td>
					<td>
						<label>Tax</label>
					</td>
					<td>
						<label>Total</label>
					</td>
				</tr><?php
				for ($count=1; $count <= $totalRec; $count++) { 
					if(isset($_POST['txtName-'.$count])){
						?><tr >
							<td>
								<div><?php
								print($_POST['txtName-'.$count]);
								?></div>
							</td>
							<td>
								<div><?php
								print($_POST['txtQuantity-'.$count]);
								?>
								</div>
							</td>
							<td>
								<div><?php
								print($_POST['txtUnitPrice-'.$count]);
								?></div>
							</td>
							<td>
								<div><?php
								print($_POST['slctTax-'.$count]);
								?>%
								</div>
							</td>
							<td>
								<div >
								<?php print($_POST['hidLineTotal-'.$count]);
								?></div>
							</td>
						</tr><?php
					}
				}
				?><tr>
					<td colspan="3">&nbsp</td>
					<td colspan="1">
						<div>
							<label>Discount Type :<?php print(ucfirst($_POST['slctDiscountType'])); ?> </label>
						</div>
					</td>
					<td colspan="1">
						<div>
							<label>Discount Value:<?php print($_POST['txtdiscount']);?></label>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4">&nbsp</td>
					<td colspan="1">
						<div>
							<label>Sub Total (with out tax):</label><span id="spanSubTotalWOT"> <?php print($_POST['hidSubTotalWOT']); ?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4">&nbsp</td>
					<td colspan="1">
						<div>
							<label>Sub Total (with tax)</label><span id="spanSubTotalWT"> <?php print($_POST['hidSubTotalWT']);?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4">&nbsp</td>
					<td colspan="1">
						<div>
							<label> Total</label><span id="spanTotal">  <?php print($_POST['hidTotal']); ?></span>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div><?php
	}
	else{
		print("Please add item from invoice page");
		exit;
	}
?></body>