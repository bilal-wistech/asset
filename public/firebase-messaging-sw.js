importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyAKCiHvqtNOMFrMQJ_wzBYS95HxQAuxuBA",
    projectId: "asset-widom",
    messagingSenderId: "470882273464",
    appId: "1:470882273464:web:b2ad59660012d1d8820c4d"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});