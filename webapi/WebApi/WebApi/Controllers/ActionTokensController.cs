using Microsoft.AspNetCore.Mvc;
using System;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ActionTokensController : BaseController<ActionTokenService, ActionToken>
    {
        public ActionTokensController(ActionTokenService service) : base(service)
        {
        }

        [HttpGet, Route("Get/{token}")]
        public ActionResult GetActionToken(string token)
        {
            if (Guid.TryParse(token, out Guid verifiedGuid))
            {
                ActionToken actionToken = Service.getActionToken(token);
                if (actionToken != null)
                {
                    if (Service.RunActionFromToken(actionToken))
                    {
                        return Ok(actionToken);
                    }
                    return Conflict();
                }
                return NotFound("Guid not found");
            }
            return UnprocessableEntity("Invalid Guid");
        }

        [HttpGet, Route("DeleteToken")]
        public ActionResult DeleteToken([FromQuery]string token)
        {
            if (Guid.TryParse(token, out Guid verifiedGuid))
            {
                var actionToken = Service.getActionToken(token);
                if (actionToken != null)
                {
                    Service.Remove(actionToken.Id);
                    return Ok();
                }
                return NotFound("Guid not found");
            }
            return UnprocessableEntity("Invalid Guid");
        }
    }
}
