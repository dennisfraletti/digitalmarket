const express = require("express")
const cookie = require("cookie-parser")
const body = require("body-parser")
const app = express()
const port = 3001;
const path = require("path")
const rotas = require("./rotas")
const cors = require("cors")

app.use(body.json())

app.use(body.urlencoded({extended: false}))

app.use(cors())

app.use(cookie())

app.use(express.static(path.join(__dirname, "_digitalmarket_CLIENT")))

app.use("/api", rotas)

app.listen(port, function() {
    console.log("Servidor do Digital Market rodando na porta: " + port)
})

