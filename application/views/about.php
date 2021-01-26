
       <table>
	<tr>
		<th>No</th>
		<th>Nama</th>
		<th>Kode</th>
	</tr>
	<?php
	if ($query->num_rows() > 0)
	{
	   foreach ($query->result() as $row)
	   {
	   ?>
	   <tr>
      <td><?php echo $row->kegiatan_id; ?></td>
      <td><?php echo $row->tahapan_id;?></td>
      <td><?php echo $row->uraian; ?></td>
	  </tr>
   <?php 
		}
	}
   ?>
   </table>
        
