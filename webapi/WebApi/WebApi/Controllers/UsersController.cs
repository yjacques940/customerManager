using Microsoft.AspNetCore.Mvc;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.DTO;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UsersController : BaseController<UserService, User>
    {
        public UsersController(UserService service) : base(service)
        {
        }

        [HttpGet, Route("Login")]
        public ActionResult<UserInformation> GetUserInformation(string email, string password)
        {
            var user = Service.GetUserInformation(email, password);
            if (user == null)
                return Unauthorized();

            return user;
        }

        [HttpGet, Route("CheckPassword")]
        public ActionResult<object> GetUserWithUserId(int userid, string password)
        {
            var user = Service.GetUserWithUserId(userid, password);
            if (user == null)
                return Unauthorized();

            return user;
        }

        [HttpPost, Route("UpdateUserEmail")]
        public ActionResult<object> UpdateUserEmail(int userid, string email)
        {
            return Ok(Service.ChangeUserEmail(userid, email));
        }
    }
}
