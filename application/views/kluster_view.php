
        <div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Anggota Kluster K-Means</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->           
            <div class="row">
            	<div class="col-lg-12">
            		<a href="<?php echo base_url(); ?>Sampling_controller/index" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Menyeimbangkan data 
            		</a>           		
            		<a href="<?php echo base_url(); ?>Sampling_controller/exportcsv/kmeans_kluster" class="btn btn-outline btn-info btn-lg btn-block" role="button">
            			Export Data Pasien KNN
            		</a>             		
            		<button type="button" class="btn btn-outline btn-danger btn-lg btn-block" data-toggle="modal" data-target="#delete_modal"							
							data-url="<?php echo base_url();?>Sampling_controller/hapus_data/kmeans_kluster"> 
							Hapus Centroid
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
                           Data Anggota Kluster K-Means
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >
                        <div class="table-responsive" >
                            <table class="table table-striped table-bordered table-hover table-responsive display nowrap" id="dataTables-example"
                            style="width:100%"  >
                                <thead>
                                     <tr>
                        				<th>ID Data</th>
                        				<th>Jarak Data ke Centroid</th>
                        				<th>Kluster</th>
                        				<th>Kelas</th>                        				                               
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
                                    	<td><?php echo $row['ID_PASIEN']; ?></td>
                                    	<td><?php echo $row['JARAK_KE_CENTROID']; ?></td>
                                        <td><?php echo $row['KLUSTER']; ?></td>
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
        <!-- Modal -->
		<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  			<div class="modal-dialog" role="document">
    			<div class="modal-content">
      				<div class="modal-header">
        				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        				<h4 class="modal-title" id="myModalLabel">Hapus Data Pasien </h4>
      				</div>
      				<div class="modal-body">
        				Apakah anda yakin akan menghapus data anggota kluster ? 
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