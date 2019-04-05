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
		
        [HttpGet, Route("HasPermission")]
        public ActionResult UserHasPermission(int idUser, string permission)
        {
            return Ok(Service.CheckIfUserHasPermission(idUser, permission));
        }

        [HttpGet, Route("CheckPassword")]
        public ActionResult GetUserWithUserId(int userId, string password)
        {
            var user = Service.GetUserWithUserId(userId, password);
            if (user == null)
                return Unauthorized();

            return Ok(user);
        }

        [HttpPost, Route("UpdateUserEmail")]
        public ActionResult UpdateUserEmail([FromBody]UserEmailInformation userEmailInformation)
        {
            return Ok(Service.UpdateUserEmail(userEmailInformation.Id, userEmailInformation.Email));
        }
    }
}
