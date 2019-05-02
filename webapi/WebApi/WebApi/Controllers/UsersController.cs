using Microsoft.AspNetCore.Mvc;
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

        [HttpPost, Route("Login")]
        public ActionResult<UserInformation> GetUserInformation([FromBody]LoginInformation loginInformation)
        {
            var user = Service.GetUserInformation(loginInformation.Email, loginInformation.Password);
            if (user == null)
                return BadRequest();

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

        [HttpPost, Route ("CheckEmailInUse")]
        public ActionResult CheckEmailInUSe([FromBody] EmailAddress emailAddress)
        {
            return Ok(Service.CheckEmailInUse(emailAddress.Email));
        }

        [HttpPost, Route("IsPasswordValid")]
        public ActionResult IsPasswordValid([FromBody] UserLoginInformation userInfo)
        {
            if(Service.IsPasswordValid(userInfo))
                return Ok();

            return Unauthorized();
        }

        [HttpPost, Route("UpdatePassword")]
        public ActionResult UpdatePassword([FromBody]PasswordsUpdateInformation passwords)
        {
            var user = new UserLoginInformation()
            {
                UserId = passwords.UserId,
                Password = passwords.OldPassword
            };

            if (passwords.OldPassword != "" && Service.IsPasswordValid(user))
            {
                user.Password = passwords.NewPassword;
                return Ok(Service.SaveNewPassword(user));
            }
            else if(passwords.UserId != 0)
            {
                user.Password = passwords.NewPassword;
                return Ok(Service.SaveNewPassword(user));
            }

            return BadRequest();
        }
    }
}
