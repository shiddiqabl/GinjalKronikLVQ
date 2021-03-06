
        <div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Proses LVQ2</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->           
            <div class="row">
            	<div class="col-lg-12">
            		<a href="<?php echo base_url(); ?>Lvq2_controller/input_lvq" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Klasifikasi LVQ2 
            		</a>
            		<a href="<?php echo base_url(); ?>Lvq2_controller/input_selection_lvq" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Klasifikasi LVQ2 Seleksi Atribut
            		</a>
            		<!-- <a href="<?php echo base_url(); ?>Lvq2_controller/create_fold" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Membuat Fold 
            		</a> -->           		            		
            		<button type="button" class="btn btn-outline btn-danger btn-lg btn-block" data-toggle="modal" data-target="#delete_modal"							
							data-url="<?php echo base_url();?>Lvq2_controller/hapus_data/hasil_pengujian_avg"> 
							Hapus Data Pengujian
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
                           Daftar Hasil Pengujian LVQ2
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >
                        <div class="table-responsive" >
                            <table class="table table-striped table-bordered table-hover table-responsive display nowrap" id="dataTables-example"
                            style="width:100%">
                                <thead>
                                     <tr>
                        				<th>ID Pengujian</th>
                        				<th>Jenis Pengujian</th>
                        				<th>Alpha Awal</th>
                        				<th>Epsilon</th>
                        				<th>Epoch Akhir Rata-rata</th>
                        				<th>Epoch Maksimal</th>
                        				<th>Tanggal Pengujian</th>                        				
                        				<th>Sensitifitas</th>
                        				<th>Spesifisitas</th>
                        				<th>Atribut</th>                        				                               				                     				
                    				</tr>
                                </thead>
                                <tbody>
                                	<?php if ($data_pengujian == FALSE): ?>
                        				<tr><td colspan="4">Tidak ada data yang tersedia</td></tr>
                   					<?php else: ?>
                                	<?php 									
									foreach($data_pengujian as $row){
								    ?>
                                	<tr>                                    	
                                    	<td><?php echo $row['ID_PENGUJIAN']; ?></td>
                                    	<td><?php echo $row['JENIS_UJI']; ?></td>
                                    	<td><?php echo $row['ALPHA_AWAL']; ?></td>
                                    	<td><?php echo $row['EPSILON']; ?></td>
                                    	<td><?php echo $row['EPOCH_AKHIR_AVG']; ?></td>
                                    	<td><?php echo $row['EPOCH_MAX']; ?></td>
                                    	<td><?php echo $row['DATE_TIME']; ?></td>                                        
                                        <td><?php echo $row['SENSITIFITAS']; ?></td>
                                        <td><?php echo $row['SPESIFISITAS']; ?></td>
                                        <td><?php echo $row['ATRIBUT']; ?></td>
                                        
                                        <!--  <td><?php echo $row['ID']; ?></td>
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
                                        <td><?php echo $row['CLASS']; ?></td>-->
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
        				Apakah anda yakin akan menghapus data pengujian ? 
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