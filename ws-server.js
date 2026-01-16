const express = require('express');
const bodyParser = require('body-parser');
const http = require('http');
const WebSocket = require('ws');

const PORT = process.env.WS_PORT || 3001;
const app = express();
app.use(bodyParser.json({ limit: '1mb' }));

const server = http.createServer(app);
const wss = new WebSocket.Server({ server });

wss.on('connection', function connection(ws, req) {
  console.log('WS client connected', req.socket.remoteAddress);
  ws.isAlive = true;
  ws.on('pong', () => { ws.isAlive = true; });
});

function broadcast(data) {
  const payload = typeof data === 'string' ? data : JSON.stringify(data);
  wss.clients.forEach(function each(client) {
    if (client.readyState === WebSocket.OPEN) {
      try {
        client.send(payload);
      } catch (e) { /* ignore send errors */ }
    }
  });
}

app.post('/notify', (req, res) => {
  const data = req.body || {};
  console.log('Notify received:', data);
  try {
    broadcast(data);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ success: false, error: err.message });
  }
});

app.get('/health', (req, res) => res.json({ ok: true }));

setInterval(() => {
  wss.clients.forEach((ws) => {
    if (!ws.isAlive) return ws.terminate();
    ws.isAlive = false;
    ws.ping(() => { });
  });
}, 30000);

server.listen(PORT, () => {
  console.log(`WebSocket server listening on port ${PORT}`);
});
