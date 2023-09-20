const mariadb = require('mariadb');
const chartjs = require('chart.js');
const http = require('http');
const host = '0.0.0.0';
const port = 8000;

const pool = mariadb.createPool({
    host: "localhost",
    user: "Wetter",
    password: "retteW",
    database: "WVS_Wetter"
});
const requestListener = async function (req, res) {
    const data = await getData();
    console.log(data);
    res.writeHead(200);
    res.end(JSON.stringify(data));
};
const server = http.createServer(requestListener);
server.listen(port, host, () => {
    console.log(`Server is running on http://${host}:${port}`);
});
async function getData () {
    let conn;
    try {
        conn = await pool.getConnection();
    	const rows = await conn.query("SELECT * FROM Data LIMIT 5");
	return rows;
    } catch (err) {
        throw err;
    } finally {
        if (conn) await conn.end();
    }
}


