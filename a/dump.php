UPDATE tb_databarang SET no_transaksi = REPLACE(no_transaksi, '/KSR/UTM/101901-10-19', '') WHERE status = 'aktif'

UPDATE tb_databarang SET no_transaksi = REPLACE(no_transaksi, (SELECT SUBSTRING(no_transaksi,7) FROM tb_databarang), '') WHERE status = 'aktif'
    
UPDATE tb_databarang SET no_transaksi = REPLACE(no_transaksi, (SELECT SUBSTRING(no_transaksi,7) FROM tb_databarang), '') WHERE status = 'aktif'

SELECT *, count(nama_barang) as jumlah from tb_databarang GROUP BY nama_barang

SELECT count(no_transaksi) as jumlah_transaksi from tb_databarang where status = 'aktif' group by no_transaksi