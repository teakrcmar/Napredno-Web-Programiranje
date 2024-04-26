var redis = require('redis');
var db = redis.createClient();

module.exports = Project;

function Project(obj) {
    for (var key in obj) {
        this[key] = obj[key];
    }
}

Project.prototype.save = function(creator_id, fn){
    if(!this.creator_id) this.creator_id = creator_id;
    if(this.id) {
        this.update(creator_id, fn);
    } else {
        let project = this;
        db.incr("project:ids", function(err,id){
            if(err) return fn(err);
            project.id = id;
            project.update(creator_id, fn);
        });
    }
};

Project.prototype.update = function(creator_id, fn){
    let project = this;
    let id = project.id;
    db.hmset("project:" + id, project.toJSON(), function(err){
        if(err) return fn(err);
        db.hset("project:uid:" + creator_id, id, id, function(err){
            fn(err);
        });
    });
};

Project.prototype.getById = function(id, fn){
    db.hgetall("project:" + id, function(err, project){
        if(err) return fn(err);
        fn(null, new Project(project));
    });
};

Project.prototype.getAllKeysByCreatorId = function(creator_id, fn){
    db.hgetall("project:uid:" + creator_id, function(err, values){
        if(err) return fn(err);
        fn(null, values);
    });
};

Project.prototype.toJSON = function(){
    return {
        id: this.id,
        name: this.name,
        desc: this.desc,
        cost: this.cost,
        tasks: this.tasks,
        date_start: this.date_start,
        date_finish: this.date_finish,
        creator_id: this.creator_id
    }
};

//CRUD za brisanje
Project.prototype.delete = function(fn){
    let project = this;
    if(project.id){
        db.del("project:" + project.id, function(err){
            if(err) return fn(err);
            db.hdel("project:uid:" + project.creator_id, project.id, function(err){
                if(err) return fn(err);
            })
            fn(null, "OK");
        })
    } else {
        return fn("No such project id");
    }
}

Project.prototype.addMember = function(member_id, fn){
    let project = this;
    if(project.id){
        db.hset("project:members:" + project.id, member_id, member_id, function(err){
            if(err) return fn(err);
            db.hset("user:members:" + member_id, project.id, project.id, function(err){
                if(err) return fn(err);
                fn(null, "OK");
            })
        })
    } else {
        return fn("No such project id");
    }
}

Project.prototype.getMembers = function(fn){
    let project = this;
    if(project.id){
        db.hgetall("project:members:" + project.id, function(err, members){
            if(err) return fn(err);
            fn(null, members);
        })
    } else {
        return fn("No such project id");
    }
};