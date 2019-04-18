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
    public class ResponsesController : BaseController<ResponseService, Response>
    {
        public ResponsesController(ResponseService service) : base(service)
        {
        }

        [HttpGet, Route("hasDoneTheSurvey/{userId}")]
        public ActionResult<bool> HasDoneTheSurvey(int userId)
        {
            return Service.HasDoneTheSurvey(userId);
        }

        [HttpPost, Route("InsertNewSurvey")]
        public ActionResult InsertNewSurveyForAUser([FromBody] ResponseInformation responses)
        {
            return Ok(Service.AddNewSurveyForAUser(responses));
        }
    }
}
