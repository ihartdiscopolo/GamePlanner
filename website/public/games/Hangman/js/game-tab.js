let accounts = {
    acc1: { name: "Account A", tokens: 5, coupons: 0 },
    acc2: { name: "Account B", tokens: 10, coupons: 2 },
    acc3: { name: "Account C", tokens: 2, coupons: 15 },
    acc4: { name: "Account D", tokens: 0, coupons: 4 }
};

let currentAccountId = 'acc1';
let myUnityInstance = null; 
let canvas = document.querySelector("#unity-canvas");

function updateUI() {
    let currentAccount = accounts[currentAccountId];
    document.getElementById("token-count").innerText = currentAccount.tokens;
    document.getElementById("coupon-count").innerText = currentAccount.coupons;
}

function switchAccount(accountId) {
    document.querySelectorAll('.account-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('btn-' + accountId).classList.add('active');
    currentAccountId = accountId;
    updateUI();
    sluitGameEnReset();
    updateLog("👤 Gewisseld naar " + accounts[currentAccountId].name);
}

function adjustStat(type, bedrag) {
    let currentAccount = accounts[currentAccountId];
    if (type === 'tokens') currentAccount.tokens = Math.max(0, currentAccount.tokens + bedrag);
    else if (type === 'coupons') currentAccount.coupons = Math.max(0, currentAccount.coupons + bedrag);
    updateUI();
}

function startUnityGame() {
    let currentAccount = accounts[currentAccountId];
    if(currentAccount.tokens <= 0) {
        updateLog("❌ Fout: " + currentAccount.name + " heeft geen tokens meer!");
        alert("Je hebt tokens nodig om te kunnen spelen!");
        return;
    }

    updateLog("🎬 Afbeelding geklikt door " + currentAccount.name + "! Unity WebGL wordt geladen...");
    document.getElementById("game-thumbnail").style.display = "none";
    document.getElementById("unity-container").style.display = "block";
    document.querySelector("#unity-loading-bar").style.display = "block";

    if (myUnityInstance == null) {
        createUnityInstance(canvas, config, (progress) => {
            document.querySelector("#unity-progress-bar-full").style.width = 100 * progress + "%";
            updateLog("⏳ Unity downloaden... " + Math.round(100 * progress) + "%");
        }).then((unityInstance) => {
            myUnityInstance = unityInstance;
            document.querySelector("#unity-loading-bar").style.display = "none";
            updateLog("🎮 Unity succesvol geladen voor " + currentAccount.name);
            
            document.querySelector("#unity-fullscreen-button").onclick = () => {
                unityInstance.SetFullscreen(1);
            };
        }).catch((message) => {
            updateLog("❌ Unity Laadfout: " + message);
            alert(message);
        });
    }
}

window.onUnityConsumeToken = function() {
    let currentAccount = accounts[currentAccountId];
    if (currentAccount.tokens > 0) {
        currentAccount.tokens--;
        updateUI();
        updateLog("🪙 Unity Seintje: Token afgeschreven voor " + currentAccount.name + ". Resterend: " + currentAccount.tokens);
    }
};

window.onUnityGainCoupons = function(aantal) {
    let currentAccount = accounts[currentAccountId];
    currentAccount.coupons += aantal;
    updateUI();
    updateLog("🎟️ Unity Seintje: " + aantal + " Coupons verdiend door " + currentAccount.name + "!");
    setTimeout(sluitGameEnReset, 4000);
};

function sluitGameEnReset() {
    document.getElementById("unity-container").style.display = "none";
    document.getElementById("game-thumbnail").style.display = "block";
    updateLog("🔄 Scherm gereset.");
}

function updateLog(bericht) {
    let logBox = document.getElementById("log");
    logBox.innerHTML += "<br>> " + bericht;
    logBox.scrollTop = logBox.scrollHeight;
}

//faas
