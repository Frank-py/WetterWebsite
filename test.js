const mariadb = require('mariadb');
const chartjs = require('chart.js');
const http = require('http');
const host = 'localhost';
const port = 8000;

//const pool = mariadb.createPool({
//    host: "localhost",
//    user: "Wetter",
//    password: "***REMOVED***",
//    database: "***REMOVED***"
//});
const pool = mariadb.createPool({
    host: "localhost",
    user: "admin",
    password: "4sdf38§$/WE3/FW§459fd2w3",
    database: "Wetter"
});
const requestListener = async function (req, res) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'OPTIONS, GET');
    res.setHeader('Access-Control-Allow-Headers', '*');
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
    	const rows = await conn.query("SELECT * FROM WeatherData LIMIT 5");
    	//const rows = await conn.query("SELECT * FROM Data LIMIT 5");
	return rows;
    } catch (err) {
        throw err;
    } finally {
        if (conn) await conn.end();
    }
}


