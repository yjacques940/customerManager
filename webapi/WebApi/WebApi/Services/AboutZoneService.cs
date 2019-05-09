using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class AboutZoneService : BaseCrudService<AboutZone>
    {
        public AboutZoneService(IWebApiContext context) : base(context)
        {
        }
    }
}
