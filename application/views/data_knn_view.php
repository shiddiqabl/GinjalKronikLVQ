
        <div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Data Penyakit Pengisian Nilai Kosong (Normalisasi KNN)</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->           
            <div class="row">
            	<div class="col-lg-12">
            		<a href="<?php echo base_url(); ?>Knn_controller/knn_norm" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Isi Nilai Kosong 
            		</a>
            		<!--  <a href="<?php echo base_url(); ?>Knn_controller/knn_norm2" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Tes KNN
            		</a>-->
            		<a href="<?php echo base_url(); ?>Sampling_controller/index" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Menyeimbangkan data 
            		</a>
            		<a href="<?php echo base_url(); ?>Knn_controller/exportcsv/data_pasien_knn" class="btn btn-outline btn-info btn-lg btn-block" role="button">
            			Export Data Pasien KNN
            		</a>             		
            		<button type="button" class="btn btn-outline btn-danger btn-lg btn-block" data-toggle="modal" data-target="#delete_modal"							
							data-url="<?php echo base_url();?>Minmax_controller/hapus_data/data_pasien_knn"> 
							Hapus Data Pasien
					</button>           		           		
            		<br>
            	</div>            	
            </div>
            <?php 
				if($this->session->flashdata('message'))
				{
					echo $this->session->flashdata('message');
				}
			?>	            
            <div class="row">
                <div  class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Data Penyakit Ginjal yang Sudah Dilakukan Normalisasi Min-Max
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >
                        <div class="table-responsive" >
                            <table class="table table-striped table-bordered table-hover table-responsive display nowrap" id="dataTables-example"
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
                        				<tr><td colspan="4">Tidak ada data yang tersedia</td></tr>
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
                                        
                                        <!--  <td><?php echo $row[0]; ?></td>
                                        <td><?php echo $row[1]; ?></td>
                                       	<td><?php echo $row[2]; ?></td>
                                        <td><?php echo $row[3]; ?></td>
                                        <td><?php echo $row[4]; ?></td>
                                        <td><?php echo $row[5]; ?></td>
                                        <td><?php echo $row[6]; ?></td>
                                        <td><?php echo $row[7]; ?></td>
                                        <td><?php echo $row[8]; ?></td>
                                        <td><?php echo $row[9]; ?></td>
                                        <td><?php echo $row[10]; ?></td>
                                        <td><?php echo $row[11]; ?></td>
                                        <td><?php echo $row[12]; ?></td>
                                        <td><?php echo $row[13]; ?></td>
                                        <td><?php echo $row[14]; ?></td>
                                        <td><?php echo $row[15]; ?></td>
                                        <td><?php echo $row[16]; ?></td>
                                        <td><?php echo $row[17]; ?></td>
                                        <td><?php echo $row[18]; ?></td>
                                        <td><?php echo $row[19]; ?></td>
                                        <td><?php echo $row[20]; ?></td>
                                        <td><?php echo $row[21]; ?></td>
                                        <td><?php echo $row[22]; ?></td>
                                        <td><?php echo $row[23]; ?></td>
                                        <td><?php echo $row[24]; ?></td>
                                        <td><?php echo $row[25]; ?></td>-->			
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
        <!-- Modal -->
		<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      				<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        				<h4 class="modal-title" id="myModalLabel">Hapus Data Pasien </h4>
      				</div>
      				<div class="modal-body">
        				Apakah anda yakin akan menghapus data pasien normalisasi KNN ? 
      				</div>
      				<div class="modal-footer">
        				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        				<a id="link_hapus" href=""><button type="button" class="btn btn-danger" >Hapus</button></a>
      				</div>
   				 </div>
  			</div>
		</div>		 
		
		<script>
		$(document).ready( function (){
			$('#delete_modal').on('show.bs.modal', function (event) {
				  var button = $(event.relatedTarget) // Button that triggered the modal				  					 
				  var url = button.data('url') 
				  console.log(url)
				  				  
				  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
				  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
				  var modal = $(this)				  
				  modal.find('#link_hapus').attr('href', url)
				})			
		});
			$('#dataTables-example').dataTable( {
				"scrollX": true
			} );
			
		</script>
		
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->