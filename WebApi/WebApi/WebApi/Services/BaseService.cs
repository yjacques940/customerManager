using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using WebApi.Data;

namespace WebApi.Services
{
    public abstract class BaseService
    {
        protected readonly IWebApiContext Context;
        protected BaseService(IWebApiContext context)
        {
            Context = context;
        }
    }
}
