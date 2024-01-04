<?php
include '../header.php';
require_once '../_config.php';
?>

<main>
    <section class="data-section">
        <h2 class="data-title">Backup Database</h2>

        <form class="backup-form" method="post" action="backup.php">
            <label for="backupFileName">Nama File Backup:</label>
            <input type="text" id="backupFileName" name="backupFileName" required>

            <button class="btn" type="submit">Backup</button>
        </form>
    </section>
</main>

<!-- footer -->
<?php
include '../footer.php';
?>
