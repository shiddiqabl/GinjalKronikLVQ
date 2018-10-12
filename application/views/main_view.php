
        <div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Data Penyakit Ginjal</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	<div class="col-lg-12">
            		<a href="<?php echo base_url(); ?>data_controller/index" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Kelola Data Penyakit
            		</a>            		
            		<br>
            	</div>
             <?php 
				if($this->session->flashdata('message')){
					echo $this->session->flashdata('message');
				}
			?>	
            </div>
            <div class="row">
                <div  class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Data Penyakit Ginjal
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >
                        <div class="table-responsive" >
                            <table class="table table-striped table-bordered table-hover display nowrap" id="dataTables-data"
                            style="width:100%"  >
                                <thead>
                                     <tr>
                        				<th>ID</th>
                        				<th>Age</th>
                        				<th>Blood Pressure</th>
                        				<th>Specific Gravity</th>
                        				<th>Albumin</th>
                        				<th>Sugar</th>
                        				<th>RBC</th>
                        				<th>Pus Cell</th>
                        				<th>Pus Cell Clumps</th>
                        				<th>Bacteria</th>
                        				<th>BGR</th>
                        				<th>Blood Urea</th>
                        				<th>Serum Cretinine</th>
                        				<th>Sodium</th>
                        				<th>Potassium</th>
                        				<th>Hemoglobin</th>
                                        <th>PCV</th>
                                        <th>WBC Count</th>
                                        <th>RBC Count</th>
                                        <th>Hypertension</th>
                                        <th>DM</th>
                                        <th>CAD</th>
                                        <th>Appetite</th>
                                        <th>Pedal Edema</th>
                                        <th>Anemia</th>
                                        <th>Class</th>
                    				</tr>
                                </thead>
                                <tbody>
                                	<?php if ($data_pasien == FALSE): ?>
                        				<tr><td colspan="4">There are currently No Addresses</td></tr>
                   					<?php else: ?>
                                	<?php 									
									foreach($data_pasien as $row){
								    ?>
                                	<tr>                                    	
                                    	<td><?php echo $row['ID']; ?></td>
                                        <td><?php echo $row['AGE']; ?></td>
                                        <td><?php echo $row['BP']; ?></td>
                                        <td><?php echo $row['SG']; ?></td>
                                        <td><?php echo $row['AL']; ?></td>
                                        <td><?php echo $row['SU']; ?></td>
                                        <td><?php echo $row['RBC']; ?></td>
                                        <td><?php echo $row['PC']; ?></td>
                                        <td><?php echo $row['PCC']; ?></td>
                                        <td><?php echo $row['BA']; ?></td>
                                        <td><?php echo $row['BGR']; ?></td>
                                        <td><?php echo $row['BU']; ?></td>
                                        <td><?php echo $row['SC']; ?></td>
                                        <td><?php echo $row['SOD']; ?></td>
                                        <td><?php echo $row['POT']; ?></td>
                                        <td><?php echo $row['HEMO']; ?></td>
                                        <td><?php echo $row['PCV']; ?></td>
                                        <td><?php echo $row['WBCC']; ?></td>
                                        <td><?php echo $row['RBCC']; ?></td>
                                        <td><?php echo $row['HTN']; ?></td>
                                        <td><?php echo $row['DM']; ?></td>
                                        <td><?php echo $row['CAD']; ?></td>
                                        <td><?php echo $row['APPET']; ?></td>
                                        <td><?php echo $row['PE']; ?></td>
                                        <td><?php echo $row['ANE']; ?></td>
                                        <td><?php echo $row['CLASS']; ?></td>									
                                    </tr>
                                <?php } ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>    
            <!-- /.row -->
            		 
		<script>
			$('#dataTables-data').DataTable( {
				"scrollX": true
			} );
			$(document).ready( function (){				
				$('#data').DataTable();
			});
		</script>
		
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->