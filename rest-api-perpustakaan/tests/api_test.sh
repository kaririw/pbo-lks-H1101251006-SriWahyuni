
#!/bin/bash

BASE_URL="http://localhost:8000/api/books"
JQ="/c/Users/user/AppData/Local/Microsoft/WinGet/Packages/jqlang.jq_Microsoft.Winget.Source_8wekyb3d8bbwe/jq.exe"

echo "=================================="
echo "   PENGUJIAN OTOMATIS REST API"
echo "=================================="

# TEST 1: GET semua buku
echo ""
echo "TEST 1: GET semua buku"

RESPONSE=$(curl -s -w "\n%{http_code}" "$BASE_URL")
STATUS="${RESPONSE##*$'\n'}"
BODY="${RESPONSE%$'\n'*}"

if [ "$STATUS" = "200" ]; then
    echo "PASS - Berhasil mengambil semua buku"
    echo "$BODY" | "$JQ" .
else
    echo "FAIL - Gagal mengambil semua buku"
fi

# TEST 2: POST tambah buku
echo ""
echo "TEST 2: POST tambah buku"

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "$BASE_URL" \
-H "Content-Type: application/json" \
-d '{"judul":"Buku Uji Otomatis","penulis":"Penulis Tes","tahun":2020,"stok":5}')

STATUS="${RESPONSE##*$'\n'}"
BODY="${RESPONSE%$'\n'*}"

if [ "$STATUS" = "201" ]; then
    echo "PASS - Berhasil menambahkan buku"
    BOOK_ID=$(echo "$BODY" | "$JQ" -r '.data.id')
    echo "ID Buku: $BOOK_ID"
else
    echo "FAIL - Gagal menambahkan buku"
    echo "$BODY"
    exit 1
fi

# TEST 3: GET detail buku
echo ""
echo "TEST 3: GET detail buku"

RESPONSE=$(curl -s -w "\n%{http_code}" "$BASE_URL/$BOOK_ID")
STATUS="${RESPONSE##*$'\n'}"
BODY="${RESPONSE%$'\n'*}"

if [ "$STATUS" = "200" ]; then
    echo "PASS - Berhasil mengambil detail buku"
    echo "$BODY" | "$JQ" .
else
    echo "FAIL - Gagal mengambil detail buku"
fi

# TEST 4: PUT ubah buku
echo ""
echo "TEST 4: PUT ubah buku"

RESPONSE=$(curl -s -w "\n%{http_code}" -X PUT "$BASE_URL/$BOOK_ID" \
-H "Content-Type: application/json" \
-d '{"judul":"Buku Uji Otomatis Revisi","penulis":"Penulis Tes","tahun":2021,"stok":10}')

STATUS="${RESPONSE##*$'\n'}"
BODY="${RESPONSE%$'\n'*}"

if [ "$STATUS" = "200" ]; then
    echo "PASS - Berhasil mengubah buku"
else
    echo "FAIL - Gagal mengubah buku"
    echo "$BODY"
fi

# TEST 5: DELETE hapus buku
echo ""
echo "TEST 5: DELETE buku"

RESPONSE=$(curl -s -w "\n%{http_code}" -X DELETE "$BASE_URL/$BOOK_ID")
STATUS="${RESPONSE##*$'\n'}"
BODY="${RESPONSE%$'\n'*}"

if [ "$STATUS" = "200" ]; then
    echo "PASS - Berhasil menghapus buku"
else
    echo "FAIL - Gagal menghapus buku"
    echo "$BODY"
fi

# TEST 6: Validasi data kosong
echo ""
echo "TEST 6: Validasi data kosong"

RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "$BASE_URL" \
-H "Content-Type: application/json" \
-d '{"judul":"","penulis":"Penulis Tes","tahun":2020,"stok":5}')

STATUS="${RESPONSE##*$'\n'}"
BODY="${RESPONSE%$'\n'*}"

if [ "$STATUS" = "422" ]; then
    echo "PASS - Validasi data kosong berhasil"
    echo "$BODY" | "$JQ" .
else
    echo "FAIL - Validasi tidak berjalan sesuai harapan"
    echo "$BODY"
fi

echo ""
echo "=================================="
echo "       PENGUJIAN SELESAI"
echo "=================================="
