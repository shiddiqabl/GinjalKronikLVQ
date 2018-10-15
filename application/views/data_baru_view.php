		<div id="page-wrapper">
			<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Tambah Data Pasien Baru</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	<div class="col-lg-12">
                    <div class="panel panel-default">
                    	<div class="panel-heading">
                           Form Data Pasien Baru
                       	</div>
                       	<div class="panel-body">
                       		<div class="row">
                       		<div class="col-lg-6">							
								<form method="post" action="<?php echo base_url() ?>data_controller/importcsv"  enctype="multipart/form-data">																	
									<div class="form-group">
										<label>Pilih Data</label>
										<input type="file" name="data_pasien">
									</div>															
									<input class="btn btn-primary" type="submit" value="Upload" name="submit">
									<a href="<?php echo base_url();?>data_controller/index" class="btn btn-danger" role="button">Batal</a>									
								</form>				
							</div>
							</div>
                            <!-- /.row (nested) -->
						</div>
                        <!-- /.panel-body -->
					</div>
                    <!-- /.panel -->
                </div>
			</div>    
            <!-- /.row -->
		</div>
        <!-- /#page-wrapper -->
