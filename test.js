const mariadb = require('mariadb');
const express = require('express');
const cors = require('cors');
const host = 'localhost';
const port = 8000;


const app = express();
app.use(cors({
    origin: '*'
}));
app.get("/data", async function (req, res) {
    graphType = req.query.graphType;
    const data = await getData(graphType);
    res.send(JSON.stringify(data));
});
//const pool = mariadb.createPool({
//    host: "localhost",
//    user: "Wetter",
//    password: "***REMOVED***",
//    database: "***REMOVED***"
//});
app.listen(port, function () {
  console.log(`Example app listening on port ${port}!`);
});
const pool = mariadb.createPool({
    host: "localhost",
    user: "admin",
    password: "4sdf38§$/WE3/FW§459fd2w3",
    database: "Wetter"
});
async function getData (graphType) {
    let conn;
    try {
        conn = await pool.getConnection();
    	const rows = await conn.query(`SELECT ${graphType}, Timestamp FROM WeatherData LIMIT 5`);
    	//const rows = await conn.query("SELECT * FROM Data LIMIT 5");
	return rows;
    } catch (err) {
        throw err;
    } finally {
        if (conn) await conn.end();
    }
}


