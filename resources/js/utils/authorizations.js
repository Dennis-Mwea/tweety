let user = window.App ? window.App.user : []

module.exports = {
    owns(model, prop = 'user_id') {
        return parseInt(model[prop]) === user.id
    }
}
