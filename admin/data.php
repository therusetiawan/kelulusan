<?php
session_start();
if(isset($_SESSION['logged']) && !empty($_SESSION['logged'])){
include "../database.php";
include '_header.php';
?>

<div class="container">
	<h2>Data Kelulusan</h2><hr>
	<div class="row col-sm-8">
		<form class="form-horizontal well" method="post" action="data_upload.php" enctype="multipart/form-data">
			<div class="form-group">
				<label for="importCsv" class="col-sm-3 control-label">CSV/Excel File</label>
				<div class="col-sm-9">
					<div class="fileinput fileinput-new input-group" data-provides="fileinput">
						<div class="form-control" data-trigger="fileinput">
							<i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
						</div>
						<span class="input-group-addon btn btn-default btn-file">
							<span class="fileinput-new">Pilih file</span>
							<span class="fileinput-exists">Ganti</span>
							<input type="file" name="file" required>
						</span>
						<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Buang</a>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" name="submit" class="btn btn-default">Upload</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="container">
		<table class="table table-bordered" id="myTable">
			<thead>
				<tr>
					<th>No</th>
					<th>NIS</th>
					<th>Nama Siswa</th>
					<th>Tanggal Lahir</th>
					<th>Kelas</th>
					<th>Status</th>
					<th>Surat Keterangan</th>
					<th>Nilai</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$qsiswa = mysqli_query($db_conn,"SELECT * FROM siswa");
			
			if(mysqli_num_rows($qsiswa) > 0){
				$i = 1;
				while($data = mysqli_fetch_array($qsiswa)){
					echo '<tr>';
					echo '<td>'.$i++.'</td>';
					echo '<td>'.$data['no_ujian'].'</td>';
					echo '<td>'.$data['nama'].'</td>';
					echo '<td>'.$data['tgl_lahir'].'</td>';
					echo '<td>'.$data['kelas'].'</td>';
					echo '<td>';
					echo ($data['status']==1) ? '<span class="badge-success">Lulus</span>' : '<span class="badge-danger">Tidak Lulus</span>';
					echo '</td>';
					echo '<td><a href="'.$data['suket_url'].'" target="blank">'.$data['suket_url'].'</a></td>';
					echo '<td><a href="'.$data['nilai_url'].'" target="blank">'.$data['nilai_url'].'</a></td>';
					echo '</tr>';
				}
			} else {
				echo '<tr><td colspan="8"><em>Belum ada data! Segera lakukan upload data.</em></td></tr>';
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<?php
include '_footer.php';
} else {
	header('Location: ./login.php');
}
?>