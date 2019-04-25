using Microsoft.AspNetCore.Mvc;
using WebApi.Models;
using WebApi.Services;

namespace WebApi.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class FollowUpsController : BaseController<FollowUpService, FollowUp>
    {
        public FollowUpsController(FollowUpService service) : base(service)
        {
        }

        [HttpPost, Route("AddNewFollowUp")]
        public ActionResult AddNewFollowUp([FromBody]FollowUp followUp)
        {
            if(followUp != null)
                return Ok(Service.AddNewFollowUp(followUp));

            return BadRequest();
        }

        [HttpPost, Route("GetFollowUpWithId")]
        public ActionResult GetFollowUpWithId([FromBody] int id)
        {
            if(id != 0)
                return Ok(Service.GetFollowUpWithId(id));

            return BadRequest();
        }
    }
}
