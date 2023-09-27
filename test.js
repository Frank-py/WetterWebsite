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
    amount = req.query.amount;
    interval = req.query.interval;
    const data = await getData(graphType, amount, interval);
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
    user: "***REMOVED***",
    password: "***REMOVED***",
    host: "***REMOVED***",
    port: ***REMOVED***,
    database: "***REMOVED***"
});
async function getData (graphType, amount, interval) {
    let conn;
    try {
        conn = await pool.getConnection();
        console.log(graphType);
    	const rows = await conn.query(`SELECT ${graphType}, timestamp FROM WeatherData WHERE Timestamp > DATE_SUB(NOW(), INTERVAL ${amount} ${interval})`);
    	//const rows = await conn.query("SELECT * FROM Data LIMIT 5");
	return rows;
    } catch (err) {
        throw err;
    } finally {
        if (conn) await conn.end();
    }
}


