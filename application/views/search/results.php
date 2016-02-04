
<?php if(isset($nameMatches) or isset($breweryMatches) or isset($typeMatches)): ?>
	
	<!-- create a table for results -->
	<table border=1>Results
	<tr><th>Name</th><th>Brewery</th><th>Type</th></tr>

	<?php if(isset($nameMatches)): ?>
		<!-- create header for matched-by-name -->
		<tr><td colspan='3'><span style='font-weight:bold'>Matches Name</span></td></tr>
		<?php foreach ($nameMatches as $nameMatch_item) : ?>
			<tr>
				<td><?php echo $nameMatch_item["Name"] ?></td>
				<td><?php echo $nameMatch_item["Brewery"] ?></td>
				<td><?php echo $nameMatch_item["Type"] ?></td>
			</tr> 
			
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if(isset($breweryMatches)): ?>
		<!-- create header for matched-by-brewery -->
		<tr><td colspan='3'><span style='font-weight:bold'>Matches Brewery</span></td></tr>
		<?php foreach ($breweryMatches as $breweryMatch_item) : ?>
			<tr>
				<td><?php echo $breweryMatch_item["Name"] ?></td>
				<td><?php echo $breweryMatch_item["Brewery"] ?></td>
				<td><?php echo $breweryMatch_item["Type"] ?></td>
			</tr>
			
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if(isset($typeMatches)): ?>
		<!-- create header for matched-by-type -->
		<tr><td colspan='3'><span style='font-weight:bold'>Matches Type</span></td></tr>
		<?php foreach ($typeMatches as $typeMatch_item) : ?>
			<tr>
				<td><?php echo $typeMatch_item["Name"] ?></td>
				<td><?php echo $typeMatch_item["Brewery"] ?></td>
				<td><?php echo $typeMatch_item["Type"] ?></td>
			</tr>
			
		<?php endforeach; ?>
	<?php endif; ?> 
	</table>

<?php endif; ?>



<!--<?php foreach ($news as $news_item) : ?>
    <h3><?php echo $news_item['Name']; ?> - <?php echo $news_item['Brewery'];?></h3>
    <p><?php echo $news_item['Type']; ?>
    
    <?php echo $news_item['Alcohol_By_Volume']; ?></p>
    
    
<?php endforeach; ?>-->



<!--
echo "<table border=1>Matches Name<tr><th>Name</th><th>type</th></tr>";
		if(array_key_exists('nameMatches', $searchResults)) {
			echo "<tr><td colspan='2'><span style='font-weight:bold'>Matches Name</span></td></tr>";
			foreach ($searchResults->nameMatches as $data) {
				if (!is_null($data)) {
					echo "<tr><td>$data->Name</td>";
					echo "<td>$data->Type</td></tr>";
				}
			}
		}
		if(array_key_exists('typeMatches', $searchResults)) {
			echo "<tr><td colspan='2'><span style='font-weight:bold'>Matches Type</span></td></tr>";
			foreach ($searchResults->typeMatches as $typeData) {
				if(!is_null($typeData)) {
					echo "<tr><td>$typeData->Name</td>";
					echo "<td>$typeData->Type</td></tr>";			
				}
			}
		}
		if(array_key_exists('breweryMatches', $searchResults)) {
			echo "<tr><td colspan='2'><span style='font-weight:bold'>Matches Brewery</span></td></tr>";
			foreach ($searchResults->breweryMatches as $breweryData) {
				if(!is_null($breweryData)) {
					echo "<tr><td>$breweryData->Name</td>";
					echo "<td>$breweryData->Type</td></tr>";
				}
			}
		}-->