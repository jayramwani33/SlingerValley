//jshint esversion:6

const express = require("express");
const bodyParser = require("body-parser");
const ejs = require("ejs");
const _ = require("lodash");

require('dotenv').config()

//Discord.JS Starts
const { Client, Events, GatewayIntentBits, EmbedBuilder, messageLink, Message } = require('discord.js');

const client = new Client({ intents: [GatewayIntentBits.Guilds] });
//Discord.JS Ends

const app = express();

app.set('view engine', 'ejs');

app.use(bodyParser.urlencoded({extended: true}));
app.use(express.static("slinger"));

app.get("/", function(req, res){
  res.sendFile(__dirname + "index.html")
});

app.post("/support", async function(req, res){
  const guild = client.guilds.cache.get('1044991472258252829');
  const member = guild.members.cache.get(req.body.discord);
  const channel = guild.channels.cache.get(req.body.subject=="Report"?'1138126714074042471':'1138126730201141248')
  
  if (!member) {
    return res.render("sumbit", {
      success: "FAIL",
      reason: "User with that ID doesn't exist in our server!"
    });
  }

  res.render("sumbit", {
    success: "Success",
    reason: "Our Support Team has received your messsage, Thank You for taking the time to contact us!"
  });


  const supportEmbed = new EmbedBuilder()
    .setColor('#424549')
    .setTitle(req.body.subject=='Report'?'Report':'Support')
    .setDescription(req.body.msg)
    .setAuthor({ name: member.user.username, iconURL: member.user.avatarURL({ format: 'png', size: 256 }) })
    .addFields(
      { name: 'Attachement', value: req.body.links },
    )
  
  member.user.send("Your " + req.body.subject + " Query has reached our staff team. You will find the responses of the said query in this channel!")

  channel.send({ embeds: [supportEmbed] });

  console.log(req.body)
});

app.listen(3000, function() {
  console.log("Server started on port 3000");
});

//Discord.JS

client.once(Events.ClientReady, c => {
	console.log(`Ready! Logged in as ${c.user.tag}`);
});

client.login(process.env.DISCORD_TOKEN);

