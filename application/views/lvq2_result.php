
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
            		<a href="<?php echo base_url(); ?>Lvq2_controller/index" class="btn btn-outline btn-success btn-lg btn-block" role="button">
            			Menu Pelatihan
            		</a>     		           		
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
				 <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Hasil Pengujian Rata-rata
                        </div>
                        <div class="panel-body">
                        	<label>Alpha Awal</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['alpha_awal']?></p>
                           	<label>Epsilon</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['epsilon']?></p>
                           	<label>Epoch Maksimal</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['max_epoch']?></p>
                            <label>Akurasi</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['akurasi_avg']?></p>
                           	<label>Error Rate</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['error_avg']?></p>
                           	<label>Sensitifitas</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['sensitifitas_avg']?></p>
                           	<label>Spesifisitas</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['spesifisitas_avg']?></p>
                           	<label>Runtime</label>
                           	<p class="form-control-static"><?php echo $hasil_uji_avg['runtime']?></p>
                        </div>                        
                    </div>
                </div>
			</div>	            
            <div class="row">
                <div  class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Hasil Pengujian per Fold
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >
                        <div class="table-responsive" >
                            <table class="table table-striped table-bordered table-hover table-responsive display nowrap" id="dataTables-example"
                            style="width:100%"  >
                                <thead>
                                     <tr>
                        				<th>ID Fold</th>
                        				<th>Alpha Awal</th>
                        				<th>Alpha Akhir</th>
                        				<th>Epsilon</th>
                        				<th>Epoch Maksimal</th>
                        				<th>Epoch Akhir</th>                        				
                        				<th>True Positive</th>
                        				<th>False Positive</th>
                        				<th>True Negative</th>
                        				<th>False Negative</th>
                        				<th>Akurasi</th>
                        				<th>Error Rate</th>
                        				<th>Sensitifitas</th>
                        				<th>Spesifisitas</th>
                        				<th>W1</th>
                        				<th>W2</th>                                        
                    				</tr>
                                </thead>
                                <tbody>
                                	<?php if ($hasil_uji_fold == FALSE): ?>
                        				<tr><td colspan="4">Tidak ada data yang tersedia</td></tr>
                   					<?php else: ?>
                                	<?php 									
									foreach($hasil_uji_fold as $row){
								    ?>
                                	<tr>                                    	
                                    	<td><?php echo $row['ID_FOLD']; ?></td>
                                        <td><?php echo $row['ALPHA_AWAL']; ?></td>
                                        <td><?php echo $row['ALPHA_AKHIR']; ?></td>
                                        <td><?php echo $row['EPSILON']; ?></td>  
                                        <td><?php echo $row['EPOCH_MAX']; ?></td>
                                        <td><?php echo $row['EPOCH_AKHIR']; ?></td>                                                                              
                                        <td><?php echo $row['TRUE_POS']; ?></td>
                                        <td><?php echo $row['FALSE_POS']; ?></td>
                                        <td><?php echo $row['TRUE_NEG']; ?></td>
                                        <td><?php echo $row['FALSE_NEG']; ?></td>
                                        <td><?php echo $row['AKURASI']; ?></td>
                                        <td><?php echo $row['ERROR']; ?></td>
                                        <td><?php echo $row['SENSITIFITAS']; ?></td>
                                        <td><?php echo $row['SPESIFISITAS']; ?></td>
                                        <td><?php echo $row['W1']; ?></td>
                                        <td><?php echo $row['W2']; ?></td>  
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
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->