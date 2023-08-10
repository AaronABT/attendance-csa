
var CLIENT_ID = '893961761740-2tgbbo3v6i9n8e6ltb6dempbk1di6rqh.apps.googleusercontent.com';
var API_KEY = 'AIzaSyCWJBEKHO_rBd2fpzL1bgomLAyfIc8XmIA';

// Initialize the Google API client
gapi.load('client:auth2', initClient);

function initClient() {
  gapi.client.init({
    apiKey: API_KEY,
    clientId: CLIENT_ID,
    discoveryDocs: ['https://sheets.googleapis.com/$discovery/rest?version=v4', 'https://www.googleapis.com/discovery/v1/apis/forms/v1/rest'],
    scope: 'https://www.googleapis.com/auth/forms https://www.googleapis.com/auth/spreadsheets.readonly'
  }).then(function () {
    gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
    updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
  });
}

function updateSigninStatus(isSignedIn) {
  if (isSignedIn) {
    getForms();
  } else {
    document.getElementById('signin-button').innerHTML = '<button class="button is-primary" onclick="signIn()">Sign In with Google</button>';
    document.getElementById('signout-button').innerHTML = '';
    document.getElementById('forms-container').innerHTML = '';
    document.getElementById('responses-container').innerHTML = '';
  }
}

function signIn() {
  gapi.auth2.getAuthInstance().signIn();
}

function signOut() {
  gapi.auth2.getAuthInstance().signOut();
}

function getForms() {
  gapi.client.forms.forms.list().then(function(response) {
    var forms = response.result.items;
    var formsHtml = '';
    for (var i = 0; i < forms.length; i++) {
      var form = forms[i];
      var formId = form.formId;
      var formTitle = form.title;
      formsHtml += '<div class="box"><h5 class="title is-5">' + formTitle + '</h5><button class="button is-info" onclick="getResponses(\'' + formId + '\')">View Responses</button></div>';
    }
    document.getElementById('signin-button').innerHTML = '';
    document.getElementById('signout-button').innerHTML = '<button class="button is-danger" onclick="signOut()">Sign Out</button>';
    document.getElementById('forms-container').innerHTML = formsHtml;
    document.getElementById('responses-container').innerHTML = '';
  });
}

function getResponses(formId) {
  gapi.client.sheets.spreadsheets.values.get({
    spreadsheetId: formId,
    range: 'Sheet1'
  }).then(function(response) {
    var rows = response.result.values;
    var jsonData = {};
    for (var i = 1; i < rows.length; i++) {
      var row = rows[i];
      jsonData[row[0]] = row.slice(1);
    }
    document.getElementById('responses-container').innerHTML = '<pre>' + JSON.stringify(jsonData, null, 2) + '</pre>';
  });
}
