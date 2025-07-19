<style>
    .profile-image img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ddd;
    }

.profile-info {
    padding-top: 10px;
    color: white;
}

</style>

<div class="page-sidebar">
    <div class="profile text-center py-4">
        <div class="profile-image">
            <img src="../assets/back/img/admin.png" alt="Admin" />
        </div>
        <div class="profile-info">
            <span class="font-weight-bold"><?= $user['nama_lengkap'] ?></span><br>
            <small class="text-muted"><?= strtoupper($user['role']) ?></small>
        </div>
    </div>

    <ul class="side-menu metismenu">
        <li class="heading">MENU UTAMA</li>

        <li>
            <a href="index.php"><i class="sidebar-item-icon fa fa-home"></i>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="data_siswa.php"><i class="sidebar-item-icon fa fa-users"></i>
                <span class="nav-label">Data Siswa</span>
            </a>
        </li>

        <li>
            <a href="data_mapel.php"><i class="sidebar-item-icon fa fa-users"></i>
                <span class="nav-label">Data Mapel</span>
            </a>
        </li>


        <li>
            <a href="data_user.php"><i class="sidebar-item-icon fa fa-users"></i>
                <span class="nav-label">Data User</span>
            </a>
        </li>

        <li>
            <a href="data_nilai.php"><i class="sidebar-item-icon fa fa-graduation-cap"></i>
                <span class="nav-label">Data Nilai</span>
            </a>
        </li>

        <li>
            <a href="pengaturan.php"><i class="sidebar-item-icon fa fa-cogs"></i>
                <span class="nav-label">Pengaturan</span>
            </a>
        </li>

        <li>
            <a href="logout.php"><i class="sidebar-item-icon fa fa-power-off"></i>
                <span class="nav-label">Keluar</span>
            </a>
        </li>
    </ul>
</div>
