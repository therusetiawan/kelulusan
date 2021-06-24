<?php
include "database.php";
$que = mysqli_query($db_conn, "SELECT * FROM konfigurasi");
$hsl = mysqli_fetch_array($que);
$timestamp = strtotime($hsl['tgl_pengumuman']);
// menghapus tags html (mencegah serangan jso pada halaman index)
$sekolah = strip_tags($hsl['sekolah']);
$tahun = strip_tags($hsl['tahun']);
$tgl_pengumuman = strip_tags($hsl['tgl_pengumuman']);
//echo $timestamp;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="aplikasi sederhana untuk menampilkan pengumuman hasil ujian nasional secara online">
    <meta name="author" content="slamet.bsan@gmail.com">
    <title>Raport Online</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jasny-bootstrap.min.css" rel="stylesheet">
	<link href="css/kelulusan.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="./">Raport Online <?=$sekolah; ?></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="./">Home</a></li>
              </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    
    <div class="container">
        <h2>Raport Online <?= $tahun; ?></h2>
		<!-- countdown -->
		
		<div id="clock" class="lead"></div>
		
		<div id="xpengumuman">
		<?php
		if(isset($_POST['submit'])){
			//tampilkan hasil queri jika ada
			$nis = stripslashes($_POST['nis']);
			
			$hasil = mysqli_query($db_conn,"SELECT * FROM siswa WHERE no_ujian='$nis'");
			if(mysqli_num_rows($hasil) > 0){
				$data = mysqli_fetch_array($hasil);
				// update count
				$count = $data['count'] + 1;
				mysqli_query($db_conn, "UPDATE siswa SET count='$count' WHERE no_ujian='$nis'")
				
		?>
			<table class="table table-bordered">
				<tr><td>NIS</td><td><?= htmlspecialchars($data['no_ujian']); ?></td></tr>
				<tr><td>Nama Siswa</td><td><?= htmlspecialchars($data['nama']); ?></td></tr>
				<tr><td>Kelas</td><td><?= htmlspecialchars($data['kelas']); ?></td></tr>
			</table>
			
			<?php
			if( $data['status'] == 1 ){
				echo '<div class="alert alert-success" role="alert"><strong>SELAMAT !</strong> Anda dinyatakan NAIK KELAS.</div>';
				echo '<br><br>';
				echo '<center>';
				echo '<div class="btn-group" role="group" aria-label="Basic example">
					  <a href="'.$data['raport_url'].'" target="blank"><button type="button" class="btn btn-primary">Download Raport</button></a>
					</div>';
				echo '</center>';
			} else {
				echo '<div class="alert alert-danger" role="alert"><strong>MAAF !</strong> Anda dinyatakan TIDAK NAIK KELAS.</div>';
				echo '<br><br>';
				echo '<center>';
				echo '<div class="btn-group" role="group" aria-label="Basic example">
					  <a href="'.$data['raport_url'].'" target="blank"><button type="button" class="btn btn-primary">Download Raport</button></a>
					</div>';
				echo '</center>';
			}	
			?>
			
		<?php
			} else {
				echo '<p style="color: red">Data tidak ditemukan! Periksa kembali Nomor Induk Siswa (NIS) atau lapor ke Wali Kelas.</p>';
				//tampilkan pop-up dan kembali tampilkan form
			}
		} else {
			//tampilkan form input nomor ujian
		?>
	<p>Masukkan data diri anda!</p>
        <form method="post" class="form-horizontal">
            <div class="form-group">
		<label class="col-sm-4 control-label">Nomor Induk Siswa</label>
		<div class="col-sm-6">
                    <input type="number" name="nis" class="form-control" required>
		</div>
            </div>
            <div class="form-group">
		<div class="col-sm-10">
		    <span class="input-group-btn">
			<button class="btn btn-primary pull-right" type="submit" name="submit">Periksa!</button>
		    </span>
		</div>
            </div>
        </form>
		<?php
		}
		?>
		</div>
    </div><!-- /.container -->
	
	<footer class="footer">
		<div class="container">
			<p class="text-muted">&copy; <?= $tahun; ?> &middot; Tim IT <?= $sekolah; ?></p>
		</div>
	</footer>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/jasny-bootstrap.min.js"></script>
	<script type="text/javascript">
	var skrg = Date.now();
	$('#clock').countdown("<?= $tgl_pengumuman; ?>", {elapse: true})
	.on('update.countdown', function(event) {
	var $this = $(this);
	if (event.elapsed) {
		$( "#xpengumuman" ).show();
		$( "#clock" ).hide();
	} else {
		$this.html(event.strftime('Dapat dilihat: <span>%H Jam %M Menit %S Detik</span> lagi'));
		$( "#xpengumuman" ).hide();
	}
	});

	</script>
</body>
</html>
