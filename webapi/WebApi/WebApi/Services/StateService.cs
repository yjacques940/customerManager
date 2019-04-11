using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;
using WebApi.Models;

namespace WebApi.Services
{
    public class StateService : BaseCrudService<State>
    {
        public StateService(IWebApiContext context) : base(context)
        {
        }
    }
}
